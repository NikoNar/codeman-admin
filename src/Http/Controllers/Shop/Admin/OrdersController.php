<?php

namespace Codeman\Admin\Http\Controllers\Shop\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Codeman\Admin\Models\Shop\Order;
use App\Http\Controllers\PonyExpressController;
use Codeman\Admin\OrderStatus\StatusEnum;
use App\Http\Controllers\Admin\ShopSettings;
use App\Mail\StatusTemplateEmail;
use App\Models\EmailTemplate;
use App\Models\Group;
use App\Models\ShopSetting;
use App\Service\PonyExpressService;
use Codeman\Admin\Helpers\EmailShortCode;
use Illuminate\Support\Facades\Mail;
use App\Models\OrderHistory;
use Codeman\Admin\Models\Shop\OrderItem;
use Illuminate\Cache\RedisTaggedCache;
use Codeman\Admin\Models\Shop\Variation;

class OrdersController extends Controller
{

    public function __construct(Order $order, User $user)
    {
        $this->middleware('admin');
        $this->order = $order;
        $this->user = $user;
        $this->module = 'orders';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $orders = $this->order
        ->with('transactions','items', 'user','payment_method','delivery_option')
        ->orderBy('created_at', 'desc')
        ->paginate(15);
        return view('admin-panel::shop.orders.index', [
            'resources' => $orders,
            'module' => $this->module,
            'statuses' => StatusEnum::STATUSES,
            'status_label_classes' => StatusEnum::ORDER_STATUS_LABEL_CLASSES
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $order = $this->order
        ->with([
            'transactions',
            // 'discountcard',
            'items' => function($q){
                return $q->with(['product', 'variation']);
            }
        ])
        ->find($id);
        if(!$order){
            return abort(404);
        }

        $order_tracking_p = new PonyExpressController();
        $order_tracking = $order_tracking_p->getOrderById($id);
        $orders_status_template = $this->getStatusesTemplate();

        $order_history = OrderHistory::where('order_id',$id)->orderBy('created_at', 'DESC')->get();

        return view('admin-panel::shop.orders.show', [
            'order' => $order,
            'order_history' => $order_history,
            'order_tracking' => $order_tracking,
            'order_status_templates' => $orders_status_template,
            'statuses' => StatusEnum::STATUSES,
            'status_label_classes' => StatusEnum::ORDER_STATUS_LABEL_CLASSES
        ]);
    }

    public function getStatusesTemplate($filter = null)
    {
        $orders_status_templates = ShopSetting::where('key','order_status_template')->get();

        $orders_status_template = $orders_status_templates
        ->pluck('value', 'key')
        ->toArray();

        foreach ($orders_status_template as $key => $value) {
            if(isJson($value)) {
                $orders_status_template[$key] = json_decode($value);
            }
        }
        return $orders_status_template;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update_status(Request $request, $id)
    {
        if($request->has('status') && array_key_exists($request->get('status'), StatusEnum::STATUSES)){

            $order = Order::with('items')->where('id', $id)->first();
            $status = !empty($request->get('status')) ? $request->get('status') : $order->status;
            $status_message = !empty($request->status_message) ? $request->status_message : $order->status_message;
            $order_history_status_message = '';
            $tracking_number = !empty($request->tracking_number) ? $request->tracking_number : $order->tracking_number;
            // $billing_emmail = "sevakzakharyan98@gmail.com"; //$order->billing_email;
            $billing_email = $order->billing_email;

            switch($request->get("status")){
                case "CANCELED_BY_US":
                    $status = "CANCELED_BY_US";
                    $order_history_status_message = "Order canceled by admin";
                    $this->sendOrderStatusEmail($request,$order,$billing_email,$status);
                    break;

                case "PAID":
                    $ponyCheck = new PonyExpressService();
                    $ponyCheckResult = $ponyCheck->pony_monitoring($request->tracking_number);


                    if(isset($ponyCheckResult['Service']['StatusList']) && empty($ponyCheckResult['Service']['StatusList']))
                    {
                        $tracking_number = null;
                        $status = "PAID";
                        $order_history_status_message = "Order paid status changed by admin";
                    }else{
                        $status = "SHIPPED";
                        $order_history_status_message = "Order shipped status changed by admin";
                    }
                    $this->sendOrderStatusEmail($request,$order,$billing_email,$status);
                    break;

                case "CONFIRMED":
                        $status = "CONFIRMED";
                        $order_history_status_message = "Order confirmed status changed by admin";
                        $this->sendOrderStatusEmail($request,$order,$billing_email,$status);
                    break;
            }

            $order->update([
                'status' => $status,
                'order_history_status_message' => $order_history_status_message,
                'status_message' => $status_message,
                'tracking_number' => $tracking_number,
            ]);

            return redirect()->back()
            ->with('success', 'Order status has been updated to <strong class="text-uppercase">'.$request->get('status').'</strong>!');

        }
        return redirect()->back()->with('fail', 'Order status has not been changed');
    }

    public static function sendOrderStatusEmail($request,$order,$billing_emmail,$status)
    {

        $shortCode = new EmailShortCode();

        $emailTemplate = EmailTemplate::where("trigger", $status)
        ->where('status', 'active')
        ->first();

        if(!$emailTemplate || $emailTemplate->body == null)
        {
            return false;
        }

        $pay_button = null;
        if($status = "CONFIRMED")
        {
            $pay_button = route('transaction.yoo_kassa', $order->id);
        }

        $email_template = $shortCode->get_converted_data($emailTemplate->body,$order);

        $details = [
            "status" => StatusEnum::STATUSES['CANCELED_BY_US'],
            "status_message" => $request->status_message,
            "email_template" => $email_template,
            "pay_button" => $pay_button ? $pay_button : null,
            "subject" => $emailTemplate->subject,
            "tracking_number" => !empty($request->tracking_number) ? $request->tracking_number : "",
            'T_name' => $emailTemplate->name,
        ];

        Mail::to($billing_emmail)->send(new StatusTemplateEmail($details));
    }


    public function loginAsUser($user_id)
    {
        $user = $this->user->find($user_id);
        if(isset($user) && !empty($user)){
            auth()->login($user);
            return redirect()->to('/user/account');
        }
    }

    public function attachVariations($order_id)
    {
        $ids = request()->get('ids');
        $is_inserted = false;
        if(!$order_id || !$ids){
            return false;
        }

        $ids = request()->get('ids');
        $variations = Variation::whereIn('id', $ids)
        ->with([
            'labels',
            'inventories' => function($q){
                return $q->with('warehouse');
            },
        ])->get();

        if($variations){
            foreach($variations as $variation)
            {
                foreach($variation->inventories as $item_inv)
                {
                    if($item_inv->quantity > 0 && $item_inv->warehouse->status == 'active')
                    {
                        OrderItem::create([
                            'order_id' => $order_id,
                            'product_id' => $variation->product_id,
                            'title' => $variation->title,
                            'variation_id' => $variation->id,
                            'price' => $variation->price,
                            'sale_price' => $variation->sale_price,
                            'qty' => 1,
                        ]);
                        $is_inserted = true;
                        break 2;
                    }
                }
            }
            if($is_inserted){
                Order::recalculateOrderPrice($order_id);
                return response()->json([
                    'status' => 'success',
                    'message' => __('Item successfully added into cart.'),
                ]);
            }
            return response()->json([
                'status' => 'error',
                'message' => __('Insufficient inventory for the selected items.'),
            ]);
        }

        return response()->json([
            'status' => 'warning',
            'message' => __('Please select at least one item.'),
        ]);

    }

    public function delete_order_item(Request $request)
    {
        if(!empty($request->order_id) && !empty($request->item_id))
        {
            $order_item = OrderItem::where([
                ['order_id',$request->order_id],
                ['id',$request->item_id]
            ])
            ->first();

            if($order_item)
            {
                OrderItem::where('id',$order_item->id)->delete();
                Order::recalculateOrderPrice($request->order_id);
                return redirect()->back()->with('success','Order Item was deleted');
            }
        }
        return redirect()->back()->with('fail','Uncorrect details');
    }


}

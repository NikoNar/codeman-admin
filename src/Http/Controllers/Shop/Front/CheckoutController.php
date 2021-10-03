<?php

namespace Codeman\Admin\Http\Controllers\Shop\Front;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductGroupOption;
use App\Models\Variation;
use App\Models\ProductVariationOption;
use App\Models\Cart;
use App\Models\DiscountCard;
use App\Models\UserAddress;
use App\Models\Order;
use App\Models\OrderItem;

use App\Mail\OrderConfirmation;
use App\Jobs\OrderConfirmationJob;
use Symfony\Component\HttpFoundation\Cookie;
use App\Http\Requests\CheckoutRequest;
use App\Http\Controllers\Controller;

class CheckoutController extends Controller
{
	public function __construct(
		Cart $cart,
		Product $product,
		ProductGroupOption $productGroupOption_pivot,
		Variation $variation, 
        Order $order,
        OrderItem $orderItem
	)
	{
		$this->cart = $cart;
		$this->product = $product;
		$this->productGroupOption_pivot = $productGroupOption_pivot;
		$this->variation = $variation;
        $this->order = $order;
        $this->orderItem = $orderItem;
        $this->lang = \App::getLocale();

	}

    public function index(UserAddress $user_address_model, DiscountCard $discountCard_model)
    {
        $session_id = $this->cart->get_cookie();
        $discount_card = null;
        
        if(auth()->check()){
            $discount_card = $discountCard_model->getDiscountCardBy('user_id', auth()->id());
        }else{
            $discount_card = $discountCard_model->getDiscountCardBy('session_id', $session_id);
        }

        $cart_items = $this->cart->get_cart_items();
        $subtotal_price = $this->cart->calculateCartSubTotalPrice($cart_items, 0);
        $cart_discount_price = $subtotal_price;
        if($discount_card && $discount_card->discount > 0){
            // making +5% for special offers
            switch ($discount_card->discount) {
                // case 10:
                //     $discount_card->discount = 14.5;
                //     break;
                case 20:
                    $discount_card->discount = 24;
                    break;
            }
            // end making +5% for special offers
            $cart_discount_price = $this->cart->calculateCartSubTotalPrice($cart_items, $discount_card ? $discount_card->discount : 0);
        }

        $user = auth()->user();

        if($user){
            $user['billing_first_name'] = $user->first_name;
            $user['billing_last_name'] = $user->last_name;
            $user['billing_phone'] = $user->phone;
            $user['billing_email'] = $user->email;
        	$shipping_address = $user_address_model->where('user_id', $user->id)->where('type', 'shipping')->first();
        	
            if($shipping_address){
	        	$user['shipping_country'] = $shipping_address->country;
	        	$user['shipping_city'] = $shipping_address->city;
	        	$user['shipping_state'] = $shipping_address->city;
	        	$user['shipping_address'] = $shipping_address->address;
	        	$user['shipping_address_2'] = $shipping_address->address_2;
        	}
        }

        if(request()->has('order') && request()->has('status') && request()->get('status') == 'transaction-failed'){
            $session_id = $this->cart->get_cookie();

            $order = $this->order
            ->where('id', request()->get('order'))
            ->where('status', 'failed');

            if(auth()->check()){
                $order = $order->where('user_id', auth()->id())->first();
            }elseif($session_id){
                $order = $order->where('session_id', $session_id)->first(); 
            }else{
                $order = $user;
            }
        }

        $shipping_options = [];
        $shipping_options['yerevan']['type'] = 'Yerevan Paid Delivery';
        // $shipping_options['yerevan']['type'] = 'Yerevan Free Delivery'; //Just for first week
        $shipping_options['yerevan']['name'] = '24 hours delivery in Yerevan';
        
        if($cart_discount_price >= 15000){
            $shipping_options['yerevan']['type'] = 'Yerevan Free Delivery';
            $shipping_options['yerevan']['coast'] = 'Free';
        }else{
            $shipping_options['yerevan']['coast'] = 1000;
            // $shipping_options['yerevan']['coast'] = 'Free'; //just for first week
        }

        $shipping_options['region']['type'] = 'Regional Delivery';
        $shipping_options['region']['name'] = 'Delivery to the regions 1-3 business days';
        $shipping_options['region']['coast'] = 1000;

        // FB Pixel tracking data for tracking ViewContent
       
        $contents = [];
        $total_cart_qty = 0;
        foreach ($cart_items as $key => $item) {
            $contents[$key]['id'] = $item->variation_id ? $item->variation_id : $item->product_id;
            $contents[$key]['quantity'] = $item->qty;
            $contents[$key]['content_type'] = $item->variation_id ? 'variation' : 'product';
            $contents[$key]['content_name'] = $item->title;
            if($item->variation_id){
                $contents[$key]['value'] = $item->variations_sale_price && $item->variations_sale_price > 0 ? $item->variations_sale_price : $item->variation_price;
            }else{
                $contents[$key]['value'] = $item->product_sale_price && $item->product_sale_price > 0 ? $item->product_sale_price : $item->product_price;
            }
            $contents[$key]['currency'] = 'AMD';

            $total_cart_qty += $item->qty;
        }

        $fb_data = [
            'contents' => $contents,
            'value' => $subtotal_price,
            'currency' => 'AMD',
            'num_items' => $total_cart_qty,
        ];

        //changing users old phone number phormat to new formatting 
        if(isset($order) && isset($order['billing_phone'])){
            $order['billing_phone'] = str_replace(' ', '', str_replace('-', '', $order['billing_phone']));
            $order['billing_phone'] = str_replace('(', '', str_replace(')', '', $order['billing_phone']));
        }else if(isset($user) && isset($user['billing_phone'])){
            $user['billing_phone'] = str_replace(' ', '', str_replace('-', '', $user['billing_phone']));
            $user['billing_phone'] = str_replace('(', '', str_replace(')', '', $user['billing_phone']));
        }
        // End FB Pixel
    	return view('checkout.index', [
    		'cart_products' => $cart_items, 
            'shipping_options' => $shipping_options,
    		'subtotal_price' => $subtotal_price,
            'total_price' => $cart_discount_price,
    		'form_data' => isset($order) ? $order : $user,
            'cart_discount_price' => isset($cart_discount_price) ? $cart_discount_price : null,
            'discount_card' => $discount_card,
            'fb_data' => json_encode($fb_data)
    	]);
    }

    public function submit(CheckoutRequest $request)
    {
        $order = $this->generateOrder($request->all());
        
        if(isset($order['status']) && $order['status'] == false){
            return redirect()->back()
            // ->with('error', __('There is something wrong with the transaction. please try again later.'))
            ->with('error', $order['message'])
            ->withInput($request->all());
        }

        switch ($request['payment_type']) {
            case 'credit_card':
                return redirect()->route('transaction.ameria', $order->id);
                break;
            case 'idram':
                return redirect()->route('transaction.idram', $order->id);
                break;            
            default:
                $order->update(['status' => 'in review']);
                return redirect()->route('order.compleated', ['order_id' => $order->id]);
            break;
        }
    }

    public function completed($order_id)
    {
        $order = $this->order->find($order_id);
        $this->cart->clear_user_cаrt();

        // FB Pixel tracking data for tracking ViewContent
        $contents = [];
        $items_total_qty = 0;
        foreach ($order->items as $key => $item) {
            $contents[$key]['id'] = $item->variation_id ? $item->variation_id : $item->product_id;
            $contents[$key]['quantity'] = $item->qty;
            $contents[$key]['content_type'] = $item->variation_id ? 'variation' : 'product';
            $contents[$key]['content_name'] = $item->title;
            $contents[$key]['value'] = $item->price;
            $contents[$key]['currency'] = 'AMD';
            $items_total_qty += $item->qty;
        }

        $fb_data = [
            'contents' => $contents,
            'value' => $order->total,
            'currency' => 'AMD',
            'num_items' => $items_total_qty,
            'order_id' => $order->id
        ];

        // End FB Pixel

        if(!$order->is_mail_sent){
            try {
                \Mail::to($order->billing_email)
                ->bcc([
                    'info@vgngroup.am', 
                    'inessa.mikayelyan@vgngroup.am', 
                    'ani.aghabekyan@vgngroup.am', 
                    'diana.andryan@vgngroup.am',
                    'orderburmunk@gmail.com',
                    // 'nikoghosyannarek@gmail.com'
                ])
                ->queue(new OrderConfirmation($order));
            } catch (Exception $e) {
                
            }
            $this->order->find($order_id)->update(['is_mail_sent' => 1]);
        }
        // dd($order);
        // dispatch(new OrderConfirmationJob($order));

        return view('checkout.completed', [
            'order' => $order,
            'fb_data' => json_encode($fb_data)
        ]);
    }

    private function generateOrder($request)
    {
        $session_id = $this->cart->get_cookie();
        $discount_card = null;
        
        if(auth()->check()){
            $discount_card = (new DiscountCard)->getDiscountCardBy('user_id', auth()->id());
        }else{
            $discount_card = (new DiscountCard)->getDiscountCardBy('session_id', $session_id);
        }

        $cart_items = $this->cart->get_cart_items();

        if($cart_items->isEmpty()){
            return ['status' => false, 'message' => __('You have not any products on your cart.')]; 
        }

        if($discount_card && $discount_card->discount > 0){
            // making +5% for special offers
            switch ($discount_card->discount) {
                // case 10:
                //     $discount_card->discount = 14.5;
                //     break;
                case 20:
                    $discount_card->discount = 24;
                    break;
            }
            // end making +5% for special offers
        }
        
        $order_subtotal_price = $this->cart->calculateCartSubTotalPrice($cart_items, $discount_card ? $discount_card->discount : 0);
        
        $shipping_states = config('armenia-province-city')['shipping'];


        if($request['shipping_state'] == 'Yerevan' && $order_subtotal_price >= 15000){
            $shipping_price = 0;
        }elseif($request['shipping_state'] == 'Yerevan' && $order_subtotal_price < 15000){
            $shipping_price = 1000;
            // $shipping_price = 0; // Just for first week
        }elseif(isset($shipping_states[$request['shipping_state']])){
            $shipping_price = $shipping_states[$request['shipping_state']];
        }else{
            return ['status' => false, 'message' => __('Please choose a shipping option')]; 
        }
        // dd($shipping_price);
        $order_total_price = $order_subtotal_price + $shipping_price;

        $orderInputs = $this->orderInputs($request, $order_subtotal_price, $order_total_price);
        $orderInputs['shipping_price'] = $shipping_price;

        if($discount_card){
            $orderInputs['discount_card'] = $discount_card->code;
            $orderInputs['discount_percent'] = $discount_card->discount;
        }
        // dd($this->cart->calculateCartItemRegularPriceSum($cart_items[0]));
        $order = $this->order->create($orderInputs);
        $this->generateOrderItems($order->id, $cart_items);
        
        return $order;

    }

    private function generateOrderItems($order_id, $cart_items)
    {
        foreach ($cart_items as $key => $item) {
            $this->orderItem->create($this->orderItemsInputs($order_id, $item));
        }
    }

    private function orderInputs($request, $subtotal, $total)
    {   
        $request['user_id'] = auth()->check() ? auth()->id() : null;
        $request['session_id'] = $this->cart->get_cookie();
        $request['ship_to_another_person'] = isset($request['ship_to_another_person']) ? 1 : 0;
        $request['subtotal'] = $subtotal;
        $request['total'] = $total;

        return $request;
    }

    private function orderItemsInputs($order_id, $item)
    {
        $inputs['order_id'] = $order_id;
        $inputs['product_id'] = $item->product_id;
        $inputs['title'] = $item->title;
        $inputs['variation_id'] = isset($item->variation_id) ? $item->variation_id : null;
        $inputs['price'] = $this->cart->calculateCartItemPrice($item);
        // $inputs['sale_price'] = ;
        $inputs['qty'] = $item->qty;
        $inputs['variation_option_type'] = $item->option_type;
        $inputs['variation_option_group'] = $item->option_group_name;
        $inputs['variation_option_value'] = $item->option_name;

        return $inputs;
    }
}
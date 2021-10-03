<?php

namespace Codeman\Admin\Http\Controllers;

use App\Http\Requests\DiscountCardRequest;
use Codeman\Admin\Models\Module;
use Illuminate\Http\Request;
use Codeman\Admin\Http\Requests\UserRequest;
use Codeman\Admin\Services\CRUDService;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\User;
use Codeman\Admin\Models\Category;
use Illuminate\Support\Facades\Response;
use Avatar;
use Illuminate\Support\Str;
use \Codeman\Admin\Models\Shop\Cart;
use App\Models\VariationSubscription;
use Illuminate\Support\Facades\DB;
use Codeman\Admin\Models\Shop\Order;
use App\Models\DiscountCard;
use App\Models\UserDiscountCard;
use Codeman\Admin\Models\Shop\UserAddress;
use Illuminate\Support\Carbon;
use function PHPUnit\Framework\isEmpty;

class UserController extends Controller
{

    protected $model;
    /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(User $model)
    {
        // $this->settings = $settings;
        $this->middleware('admin');
        $this->CRUD = new CRUDService($model);
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        return view('admin-panel::user.index', ['users' => $this->model->paginate(20) , 'dates' => $this->getDatesOfResources($this->model)]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $modules = Module::pluck('slug')->toArray();
        return view('admin-panel::user.create_edit', compact('modules'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $profile_pic_filename = Str::random(32).'.png';
        $profile_pic = Avatar::create($request->name)->save(public_path().'/images/users/'.$profile_pic_filename);
        $user = new User;
        $user->name = $request->name;
        $user->profile_pic = $profile_pic_filename;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->save();
        if($request->role){
            $user->assignRole($request->role);
        }
        if($request->permissions){
            $permissions = json_decode($request->permissions);
            foreach($permissions as $permission){
                $user->givePermissionTo($permission);
            }
        }

        return redirect()->route('user.index')->with('success', 'User Created Successfully.');
    }

    /**
     * Display user profile8.
     *
     * @param  \Codeman\Admin\Models\Shop\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function profile($id, \Codeman\Admin\Models\Shop\Order $order)
    {
        $user = $this->model->find($id);
        if(!$user){
            abort(404);
        }

        $orders = $order
        ->has('items')
        ->with([
            'transactions',
            'items' => function($q){
                $q->with([
                    'product' => function($q){
                        $q->select('id', 'title', 'price', 'sale_price', 'thumbnail', 'brand_id')
                        ->with(['brand' => function($q){
                            $q->select('id', 'title');
                        }]);
                    },
                    'variation' => function($q){
                        $q->select('id', 'product_id', 'price', 'sale_price', 'thumbnail');
                    }
                ]);
            }
        ])
        ->where('user_id', $user->id)
        ->whereNotIn('status', ['pending', 'failed'])
        ->orderBy('created_at', 'desc')
        ->get();

        $discount_cards = DiscountCard::where('status','active')->get();
        $user_discount_cards = UserDiscountCard::where('user_id', $user->id)->get();
        $v_subscription = VariationSubscription::where('user_id',$id)->count();

        $user_cards_id = [];
        $user_cards_precent = [];

        foreach($user_discount_cards as $user_card)
        {
            array_push($user_cards_id,$user_card->discount_card_id);
        }

        foreach($user_discount_cards as $user_card)
        {
            array_push($user_cards_precent,$user_card->DiscountCard->discount_percent);
        }

        return view('admin-panel::user.profile', [
            'discount_cards' => $discount_cards,
            'user_discount_cards' => $user_discount_cards,
            'user' => $user,
            'user_addresses' => $user->user_addresses,
            'user_cards_id' => $user_cards_id,
            'user_cards_precent' => $user_cards_precent,
            'variation_subscription' => $v_subscription,
            'orders' => $orders
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $modules = Module::where('module_type', 'module')->pluck('slug')->toArray();

        return view('admin-panel::user.create_edit', [
            'user' => $this->CRUD->getById($id),
            'modules' => $modules,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request,  $id)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $user = User::where('id', $id)->first();
        if($request->password){
            $request['password'] = \Hash::make($request->password);
            $user->update($request->all());
        } else {
            $user->update($request->except('password'));
        }

        if($request->role){
            $user->syncRoles($request->role);
        }
        if($request->permissions){
            $permissions = json_decode($request->permissions);
            $user->syncPermissions($permissions);
        }

        return redirect()->route('user.edit', $id)->with('success', 'User Successfully Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        if($this->CRUD->destroy($id)){
            return redirect()->back()->with('success', 'User Successfully Deleted.');
        }
    }

    /**
     * Login to user account
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function loginAsUser($user_id)
    {
        $user = $this->model->find($user_id);
        if(isset($user) && !empty($user)){
            auth()->login($user);
            return redirect()->to('/user/account');
        }
        return redirect()->back()->with('error', 'User not found.');
    }

    /**
     * Get User Orders List
     *
     * @param int $user_id
     * @param \Codeman\Admin\Models\Shop\Order $order
     * @return \Illuminate\Http\Response::JSON
     */
    public function userOrders($user_id, \Codeman\Admin\Models\Shop\Order $order)
    {
        $orders = $order
        ->has('items')
        ->with([
            'transactions',
            'items' => function($q){
                $q->with([
                    'product' => function($q){
                        $q->select('id', 'title', 'price', 'sale_price', 'thumbnail', 'brand_id')
                        ->with(['brand' => function($q){
                            $q->select('id', 'title');
                        }]);
                    },
                    'variation' => function($q){
                        $q->select('id', 'product_id', 'price', 'sale_price', 'thumbnail', 'secondary_thumbnail');
                    }
                ]);
            }
        ])
        ->where('user_id', $user_id)
        // ->whereNotIn('status', ['pending', 'failed'])
        ->orderBy('created_at', 'desc')
        ->get()
        ->toArray();

        return Response::json(['data' => $orders]);
    }

    /**
     * Get User Cart Items
     *
     * @param int $user_id
     * @param string $cart_type
     * @return \Illuminate\Http\Response::JSON
     */
    public function userCart($user_id, $cart_type = 'cart')
    {
        $cart = new Cart();
        // $cart_items = $cart->get_cart_items($cart_type, $user_id); // This function for get products

        $cart_items = $cart->get_cart_variations($cart_type, $user_id); // This function for get variations

        $subtotal_price = $cart->calculateCartSubTotalPrice($cart_items, 0);

        return Response::json(['data' => $cart_items]);
    }

    /**
     * Get User Discount/Loyalty Cards
     *
     * @param int $user_id
     * @return \Illuminate\Http\Response::JSON
     */
    public function userDiscountCard($user_id)
    {
        $user_discount_cards = UserDiscountCard::query()
        ->with('user', 'DiscountCard')
        ->where('user_id', $user_id)
        ->get()
        ->toArray();

        return Response::json(['data' => $user_discount_cards]);
    }

    /**
     * Attache Discount/Loyalty Card to user account
     *
     * @param  int  $id
     * @param  Request  $request
     * @return \Illuminate\Http\Response
     */
    public function attach_discount_card($id, Request $request)
    {
        $user_discount_cards = UserDiscountCard::where('user_id',$id)->first();
        $card_code = null;
        if($user_discount_cards)
        {
            $card_code = $user_discount_cards->card_code;
        }else{
            $card_code = mt_rand(1000000000, 9999999999);
        }

        $new_card = UserDiscountCard::create([
            'user_id' => $id,
            'discount_card_id' => $request->discount_card,
            'card_code' => $card_code,
            'status' => 'active'
        ]);

        UserDiscountCard::where([
            ['user_id',$new_card->user_id],
            ['id','!=',$new_card->id],
        ])->update(['status' => 'disabled']);

        return redirect()->back()->with('success', __('Discount Card Successfully Attached.'));
    }

    /**
     * Delete User Discount/Loyalty Card
     * After Deletinng System will anable the previusly disabled card if it's esixts.
     *
     * @param int $id
     * @return \Illuminate\Http\Response::JSON
     */
    public function delete_card($id)
    {
        if(request()->ajax())
        {
            $discount_dard = UserDiscountCard::where('id', $id)->first();
            if(!$discount_dard){
                return false;
            }
            $user_id = $discount_dard->user_id;
            $discount_dard->delete();

            // Check if user was previusly attached but disabled card, system shell enable that card.
            $user_prev_card_id = UserDiscountCard::where('user_id', $user_id)->max('id');
            if($user_prev_card_id){
                UserDiscountCard::where('id', $user_prev_card_id)->update(['status' => 'active']);
            }
            return true;
        }
        return false;
    }

    /**
     * Get User Addresses List
     *
     * @param int $user_id
     * @return \Illuminate\Http\Response::JSON
     */
    public function userAddresses($user_id)
    {
        $user_addresses = UserAddress::query()
            ->with('user')
            ->where('user_id', $user_id)
            ->get()
            ->toArray();

        return Response::json(['data' => $user_addresses]);
    }

}

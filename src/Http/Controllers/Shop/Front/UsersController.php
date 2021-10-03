<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\UserUpdateRequest;
use App\Http\Requests\UserUpdateAddressRequest;
use App\Http\Requests\PasswordUpdateRequest;

use App\Models\UserAddress;
use App\User;
use App\Models\Order;

class UsersController extends Controller
{

    public function __construct(User $user)
    {
        $this->user = $user;
    	$this->middleware('auth');
    }

    public function account()
    {
    	$user = auth()->user();
    	return view('users.account', ['user' => $user]);
    }

    public function orders(Order $order)
    {
    	$user = auth()->user();
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
        ->get()
        ->groupBy('status');
        
    	return view('users.orders', ['user' => $user, 'orders' => $orders]);
    }

    public function addresses(UserAddress $address_model)
    {
        $addresses = $address_model->where('user_id', auth()->id())->get()->groupBy('type');   
    	return view('users.addresses', [
            'addresses' => $addresses,
            'address_types' => ['billing', 'shipping']
        ]);
    }
    
    public function edit_address($type, UserAddress $address_model)
    {
    	$user = auth()->user();
        $user_address = $address_model->where('user_id', $user->id)->where('type', $type)->first();
        if(!$user_address){
            $user_address = $user;
        }
    	return view('users.edit_address', ['user_address' => $user_address, 'type' => $type]);
    }

    public function update_address(UserUpdateAddressRequest $request, UserAddress $address_model)
    {
        $request['user_id'] = auth()->id();
        $address_model->updateOrCreate(['user_id' => $request['user_id'], 'type' => $request['type']], $request->all());
	    return redirect()->route('user.addresses')->with('success', 'Your address has been updated successfully.');
    }

    public function update(UserUpdateRequest $request)
    {
        // dd($request->all());
        $inputs = $request->all();
        $user = $this->user->find(auth()->id());
        // if($request->has('phone')){
        //     $inputs['phone'] = str_replace('+', '', $inputs['phone']);
        //     $inputs['phone'] = str_replace('(', '', $inputs['phone']);
        //     $inputs['phone'] = str_replace(')', '', $inputs['phone']);
        //     $inputs['phone'] = str_replace('-', '', $inputs['phone']);
        //     $inputs['phone'] = str_replace(' ', '', $inputs['phone']);
        // }
        $inputs['receive_newsletter'] = $request->has('receive_newsletter') ?  1 : 0;
        $inputs['receive_sms'] = $request->has('receive_sms') ?  1 : 0;
        
        $user->update($inputs);
        return redirect()->back()->with('success', 'Your profile was successfully updated');
    }

    public function password()
    {
    	$user = auth()->user();
    	return view('users.password', ['user' => $user]);
    }

    public function update_password(PasswordUpdateRequest $request)
    {
        
        if(!\Hash::check($request['current_password'], auth()->user()->password)){
            return back()->with('error', __('You have entered wrong password'));
        }else{
            auth()->user()->update(['password' => \Hash::make($request->password)]);
            return back()->with('success', __('Your password was successfully changed'));
        }
    	// $user = auth()->user();
    	// return view('users.password', ['user' => $user]);
    }
}

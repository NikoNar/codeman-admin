<?php

namespace Codeman\Admin\Http\Controllers\Shop\Front;

use App\Models\DeliveryAddresses;
use Codeman\Admin\Models\Shop\UserAddress;
use Illuminate\Http\Request;
use Codeman\Admin\Models\Shop\Product;
use Codeman\Admin\Models\Shop\ProductGroupOption;
use Codeman\Admin\Models\Shop\Variation;
use Codeman\Admin\Models\Shop\ProductVariationOption;
use Codeman\Admin\Models\Shop\Cart;
use Codeman\Admin\Models\Shop\DiscountCard;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Cookie;
use App\Http\Controllers\Controller;
use App\Http\Requests\DiscountCardRequest;
use App\Models\CartRulesDiscount;
use App\Logics\Discount;
use App\Logics\DiscountRuleActionsL;
use App\Http\Requests\CartRequest;
use App\Models\DiscountRuleActionM;

class CartController extends Controller
{
	public function __construct(
		Cart $cart,
		Product $product,
		ProductGroupOption $productGroupOption_pivot,
		Variation $variation
	)
	{
		$this->cart = $cart;
		$this->product = $product;
		$this->productGroupOption_pivot = $productGroupOption_pivot;
		$this->variation = $variation;
        $this->lang = \App::getLocale();
        $this->session_id = get_user_session_id();
	}

    public function index(DiscountCard $discountCard_model)
    {
        dd('index not working, check App\Http\Controllers\CartController');
        $session_id = $this->cart->get_cookie();
        $discount_card = null;

        if(auth()->check()){
            $discount_card = $discountCard_model->getDiscountCardBy('user_id', auth()->id());
        }else{
            $discount_card = $discountCard_model->getDiscountCardBy('session_id', $session_id);
        }
        $cart_items = $this->cart->get_cart_items();

        $subtotal_price = $this->cart->calculateCartSubTotalPrice($cart_items, 0);
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

    	return view('cart.index', [
            'cart_products' => $cart_items,
            'subtotal_price' => $subtotal_price,
            'cart_discount_price' => isset($cart_discount_price) ? $cart_discount_price : null,
            'discount_card' => $discount_card,
        ]);
    }

    // Show wishlist page
    public function wishlist()
    {
        $variations_ids = $this->cart->get_wishlist_variations('wishlist')->pluck('variation_id')->toArray();
        $variations = $this->variation
        ->prepareVariationsCollection(null, null, $variations_ids, null, null)
        ->get();

        // $ids = $variations->pluck('id')->toArray();
        $variations_colors_sizes = $this->variation->getVariationsColorsAndSizesWithStock($variations_ids);

        foreach ($variations as $key => $variation) {
            $variation['properties'] = isset($variations_colors_sizes[$variation->id]) ? $variations_colors_sizes[$variation->id] : null;
        }

        // if($variations && !$variations->isEmpty()){
        //     $product_ids = $variations->pluck('product_id')->toArray();
        //     $variations_options_groupd = $this->variation
        //     ->getVariationsOptionsGrouped($product_ids, [2, 3], 'product_id');

        //     foreach ($variations as $key => $variation) {
        //         $variation['properties'] = $variations_options_groupd[$variation->product_id];
        //     }
        // }

        // foreach ($variations as $key => $item) {
        //     $item->variation['option_groups'] = $this->variation->getRelatedVariationsOfColors($item->product->id);
        //     // $item->variation['size_stock'] = $this->variation->getVariationSizesStock($item->variation);
        // }
        return view('wishlist.index', [
            'variations' => $variations
        ]);
    }


    public function add(CartRequest $request, $product_id, $variation_id = null)
    {
        if(Session::has('edit_order'))
        {
            //Logic BUG edit order __fix
            Cart::where([ ['user_id',auth()->id()], ['status',1] ])->delete();
            Cart::where([ ['user_id',auth()->id()], ['status',0] ])->update(['status'=>1]);
            Session::forget(('edit_order'));
        }


        $qty = $request->has('qty') ? $request->get('qty') : 1;
    	$session_id = $this->session_id;

    	if($product_id){
    		$product = $this->product
    		->where('id', $product_id)
    		->where('status', 'published')
    		->first();

    		if(!$product){
                if(request()->ajax()){
                    return response()->json([
                        'status' => false,
                        'btn_message' => __('Add to cart'),
                        'message' => __('No Product found.'),
                    ]);
                }
    			return redirect()->back()->with('error', "No Product found");
    		}

    		if($product->type == 'variation'){
	    		$variations_options_grouped = $this->get_variations_options_grouped($product->id);

	    		if($variations_options_grouped){
	    			$variation = $this->get_variation($request->all(), $product->id);

	    			if(!$variation){
                        if(request()->ajax()){
                            return response()->json([
                                'status' => false,
                                'btn_message' => __('Add to cart'),
                                'message' => 'Please choose one of the options.'
                            ]);
                        }
	    				return redirect()->back()->with('error', 'Please choose one of the options.');
	    			}

                    //CHECK VARIATION STOCK
                    $cart_by_variation = Cart::where([
                        ['variation_id', $variation->id],
                        ['cart_type','cart'],
                    ]);
                    if(auth()->check())
                    {
                        $cart_by_variation = $cart_by_variation->where('user_id',auth()->id())->get();
                    }else{
                        $cart_by_variation = $cart_by_variation->where('session_id',get_user_session_id())->get();
                    }
                    $cart_itmes_qty = 0;
                    foreach($cart_by_variation as $same_items)
                    {
                        $cart_itmes_qty += $same_items->qty;
                    }

                    if($variation->stock_count <= $cart_itmes_qty)
                    {
                        return response()->json([
                            'status' => false,
                            'message' => __('Not enough items in stock')
                        ]);
                    }

                    //END CHECK STOCK
	    		}
    		}

    		//check if product already was added to the cart
    		// $cart_item = $this->cart;
    		// if(auth()->check()){
    		// 	$cart_item = $cart_item->where('user_id', auth()->id());
    		// }else{
    		// 	$cart_item = $cart_item->where('session_id', $session_id);
    		// }
    		// $cart_item = $cart_item->where('product_id', $product->id);

    		// if(isset($variation) && !empty($variation)){
    		// 	$cart_item = $cart_item->where('variation_id', $variation->id);
    		// }
    		// $cart_item = $cart_item
    		// ->where('cart_type', 'cart')
    		// ->first();

    		// if(!$cart_item)
            //{

                if(isset($variation) && !empty($variation))
                {
                    if($variation->stock_count < 1)
                    {
                        return response()->json([
                            'status' => false,
                            'btn_message' => __('Add to cart'),
                            'message' => __('Sorry, This product is out of stock.')
                        ]);

                    }else if($variation->stock_count < intval($qty)){
                        return response()->json([
                            'status' => false,
                            'btn_message' => __('Add to cart'),
                            'message' => __($variation->stock_count.' items left on stock, please select less quantity')
                        ]);
                    }
                }else{
                    if($product->stock_count == 0)
                    {
                        return response()->json([
                            'status' => false,
                            'btn_message' => __('Add to cart'),
                            'message' => __('Sorry, This product is out of stock.')
                        ]);

                    }else if($product->stock_count < intval($qty)){
                        return response()->json([
                            'status' => false,
                            'btn_message' => __('Add to cart'),
                            'message' => __($product->stock_count.' items left on stock, please select less quantity')
                        ]);
                    }
                }
    			$item = $this->cart->create([
    				'user_id' => auth()->check() ? auth()->id() : null,
	    			'session_id' => $session_id,
	    			'product_id' => $product->id,
	    			'variation_id' => isset($variation) && !empty($variation) ? $variation->id : null,
                    'color_option_id' => isset($request['option']) && isset($request['option'][2])
                                            ? $request['option'][2]
                                            : null, //color
                    'size_option_id' => isset($request['option']) && isset($request['option'][3])
                                            ? $request['option'][3]
                                            : null, //size
	    			'qty' => $qty,
	    			// 'cart_type' => 'cart'
    			]);

                $exists_in_wishlist = $this->cart->where([
                    'user_id' => auth()->check() ? auth()->id() : null,
                    'session_id' => $session_id,
                    'product_id' => $product->id,
                    'variation_id' => isset($variation) && !empty($variation) ? $variation->id : null,
                    'cart_type' => 'wishlist'
                ])->first();

                if($exists_in_wishlist){
                    $exists_in_wishlist->delete();
                }

    		// }else{
      //           if(isset($variation) && !empty($variation))
      //           {
      //               if($variation->stock_count == 0)
      //               {
      //                   $cart_item->remove();
      //                   return response()->json([
      //                       'status' => false,
      //                       'btn_message' => __('Add to cart'),
      //                       'message' => __('Sorry, This product is out of stock.')
      //                   ]);

      //               }elseif($variation->stock_count < intval($qty) + $cart_item->qty ){
      //                   return response()->json([
      //                       'status' => false,
      //                       'btn_message' => __('Add to cart'),
      //                       'message' => __($variation->stock_count.' items left on stock, please select less quantity')
      //                   ]);
      //               }

      //           }else{
      //               if($product->stock_count == 0)
      //               {
      //                   $cart_item->remove();
      //                   return response()->json([
      //                       'status' => false,
      //                       'btn_message' => __('Add to cart'),
      //                       'message' => __('Sorry, This product is out of stock.')
      //                   ]);

      //               }elseif($product->stock_count < intval($qty) + $cart_item->qty){
      //                   return response()->json([
      //                       'status' => false,
      //                       'btn_message' => __('Add to cart'),
      //                       'message' => __($product->stock_count.' items left on stock, please select less quantity')
      //                   ]);
      //               }
      //           }
    		// 	$item = $cart_item->update([
	    	// 		'qty' => intval($cart_item->qty) + intval($qty),
    		// 	]);
    		// }

            if(request()->ajax()){
                $price = 0;
                if(isset($variation)){
                    $price = $variation->sale_price && $variation->sale_price > 0 ? $variation->sale_price : $variation->price;
                }else{
                    $price = $product->sale_price && $product->sale_price > 0 ? $product->sale_price : $product->price;
                }

                $category = '';
                if(isset($product->categories) && !$product->categories->isEmpty()){
                    foreach ($product->categories as $key => $cat) {
                        if($key == 0){
                            $category .= $cat->title;
                        }else{
                            $category .= ' > '. $cat->title;
                        }
                    }
                }

                $title = isset($variation) ? $variation->title : $product->title;

                if(request()->has('route_name') && request()->get('route_name') == 'cart')
                {
                    $cart_items = $this->cart->get_cart_variations();

                    foreach ($cart_items as $key => $item) {
                        $item->variation['size_stock'] = $this->variation->getVariationSizesStock($item->variation);
                    }
                    $rule_discount = null;
                    Discount::determineDiscountingBy($cart_items,$rule_discount);
                    $productCalculation = Cart::calcOrderWithWithoutCards($cart_items);
                    $cart_items_html = view('cart.parts.listing', [
                        'cart_products' => $cart_items,
                        'productCalculation' => $productCalculation,
                        'rule_discount' => $rule_discount,
                    ])->render();

                }

                return response()->json([
                    'status' => true,
                    'message' => $title. ' '. __('has been added to your cart'),
                    'btn_message' => __('Added'),
                    'cart_qty' => $this->cart->get_cart_items_qty('cart'),
                    'wishlist_qty' => $this->cart->get_cart_items_qty('wishlist'),
                    'button' => '<button type="submit" class="remove-cart-item main-btn green uppercase in-cart transition-none" data-action="remove-from-cart" data-cart-id="'.$item->id.'" data-href="'.route('remove-cart-item', $item->id).'">'.__('В корзине').'</button>',

                    'cart_items_html' => isset($cart_items_html) ? $cart_items_html : null,
                    'action_from' => request()->has('route_name') ? request()->get('route_name') : null,
                    'fb_data' => [
                        'content_name' => $product->title,
                        'content_category' => $category,
                        'content_ids' => array($product->id),
                        'content_type' => 'product',
                        'value' => $price,
                        'currency' => 'AMD',
                    ]
                ])->cookie('user_session_id', $session_id );
            }
    		return redirect()->back()->with('success', __('Product has been added to your cart.'))
            ->cookie('user_session_id', $session_id );
    	}

    	return redirect()->back()->with('error', "No Product found");
    }

    public function wishlist_add($product_id, $variation_id)
    {
        $request = request()->all();
    	$session_id = $this->cart->get_cookie();

    	if($product_id){
    		$product = $this->product
    		->where('id', $product_id)
    		->where('status', 'published')
    		->first();

    		if(!$product){
    			return redirect()->back()->with('error', __("No Product found"));
    		}

    		//check if product already was added to the cart(wishlist)
    		$cart_item = $this->cart;
    		if(auth()->check()){
    			$cart_item = $cart_item->where('user_id', auth()->id());
    		}else{
    			$cart_item = $cart_item->where('session_id', $session_id);
    		}
    		$cart_item = $cart_item->where('product_id', $product->id)->where('variation_id', $variation_id);

    		$cart_item = $cart_item
    		->where('cart_type', 'wishlist')
    		->first();

    		if(!$cart_item){
    			$item = $this->cart->create([
    				'user_id' => auth()->check() ? auth()->id() : null,
	    			'session_id' => $session_id,
	    			'product_id' => $product->id,
	    			'variation_id' => isset($variation_id) && !empty($variation_id) ? $variation_id : null,
                    'color_option_id' => isset($request['option']) && isset($request['option'][2])
                                            ? $request['option'][2]
                                            : null, //color
                    'size_option_id' => isset($request['option']) && isset($request['option'][3])
                                            ? $request['option'][3]
                                            : null, //size
	    			'qty' => 1,
	    			'cart_type' => 'wishlist'
    			]);
    		}else{
                $cart_item->delete();
                if(request()->ajax()){
                    return response()->json([
                        'status' => true,
                        'message' => __('Product has been removed form your wishlist.'),
                        'btn_message' => _('Добавить в избранное'),
                        'wishlist_qty' => $this->cart->get_cart_items_qty('wishlist')
                    ])->cookie('user_session_id', $session_id );
                }
    			return redirect()->back()->with('warning', __('Product has been removed form your wishlist.'))->cookie('user_session_id', $session_id );
    		}
            if(request()->ajax()){
                $category = '';
                if(isset($product->categories) && !$product->categories->isEmpty()){
                    foreach ($product->categories as $key => $cat) {
                        if($key == 0){
                            $category .= $cat->title;
                        }else{
                            $category .= ' > '. $cat->title;
                        }
                    }
                }
                return response()->json([
                    'status' => true,
                    'message' => __('Product has been added to your wishlist.'),
                    'btn_message' => __('В избранном'),
                    'wishlist_qty' => $this->cart->get_cart_items_qty('wishlist'),
                    'item_id' => $item->id,
                    'fb_data' => [
                        'content_name' => $product->title,
                        'content_category' => $category,
                        'content_ids' => array($product->id),
                        'content_type' => 'product',
                        'value' => $product->sale_price && $product->sale_price > 0 ? $product->sale_price : $product->price,
                        'currency' => 'AMD',
                    ]
                ])->cookie('user_session_id', $session_id );
            }
    		return redirect()->back()->with('success', __('Product has been added to your wishlist.'))->cookie('user_session_id', $session_id );
    	}
    	return redirect()->back()->with('error', "No Product found");
    }

    public function move_to_wishlist($cart_id)
    {
        $cart_item = $this->cart->where('id', $cart_id)
        ->where('cart_type', 'cart');

        if(auth()->guest()){
            $cart_item = $cart_item->where('session_id', $this->session_id)->first();
        }else{
            $cart_item = $cart_item->where('user_id', auth()->id())->first();
        }

        if(!$cart_item)
            return response()->json([
                'status' => false,
                'message' => __('Cart item not found.'),
            ]);

        $wishlist_item = $this->cart->where('product_id', $cart_item->product_id)
        ->where('variation_id', $cart_item->variation_id)
        ->where('cart_type', 'wishlist');

        if(auth()->guest()){
            $wishlist_item = $wishlist_item->where('session_id', $this->session_id)->first();
        }else{
            $wishlist_item = $wishlist_item->where('user_id', auth()->id())->first();
        }

        if($wishlist_item){
            $cart_item->delete();
        }else{
            $cart_item->update(['cart_type' => 'wishlist']);
        }

        $cart_qty = $this->cart->get_cart_items_qty('cart');
        $wishlist_qty = $this->cart->get_cart_items_qty('wishlist');

        return response()->json([
            'status' => true,
            'wishlist_qty' => $wishlist_qty,
            'cart_qty' => $cart_qty,
            'empty_messgae' => $cart_qty == 0
                                    ? view('cart.parts._empty')->render()
                                    : null,
            'action_from'  => request()->has('action-from') ? request()->get('action-from') : null,
            'message' => __('Product was moved to wishlist.'),
        ]);
    }

    public function move_to_cart($wishlist_id)
    {
        $request = request()->all();
        $wishlist_item = $this->cart->where('id', $wishlist_id)
        ->where('cart_type', 'wishlist');

        if(auth()->guest()){
            $wishlist_item = $wishlist_item->where('session_id', $this->session_id)->first();
        }else{
            $wishlist_item = $wishlist_item->where('user_id', auth()->id())->first();
        }

        if(!$wishlist_item)
            return response()->json([
                'status' => false,
                'message' => __('Cart item not found.'),
            ]);

        $request_data = request()->all();
        $variation = $this->get_variation($request_data, $wishlist_item->product_id);

        if(!$variation)
            return response()->json([
                'status' => false,
                'message' => 'Please choose one of the options.'
            ]);

        //CHECK STOCK
        $cart_by_variation = Cart::where([
            ['variation_id',$variation->id],
            ['cart_type','cart'],
        ]);
        if(auth()->check())
        {
            $cart_by_variation = $cart_by_variation->where('user_id',auth()->id())->get();
        }else{
            $cart_by_variation = $cart_by_variation->where('session_id',get_user_session_id())->get();
        }
        $cart_itmes_qty = 0;
        foreach($cart_by_variation as $same_items)
        {
            $cart_itmes_qty += $same_items->qty;
        }

        if($variation->stock_count <= $cart_itmes_qty)
        {
            return response()->json([
                'status' => false,
                'message' => __('Not enough items in stock')
            ]);
        }

        //END CHECK STOCK

        $total_qty = $this->cart->where('product_id', $variation->product_id)->where('variation_id', $variation->id);

        if(auth()->guest()){
            $total_qty = $total_qty->where('session_id', $this->session_id)->count();
        }else{
            $total_qty = $total_qty->where('user_id', auth()->id())->count();
        }

        if($variation->stock_count < 1)
        {
            return response()->json([
                'status' => false,
                'btn_message' => __('Add to cart'),
                'message' => __('Sorry, This product is out of stock.')
            ]);

        }else if($variation->stock_count <= intval($total_qty)){
            return response()->json([
                'status' => false,
                'btn_message' => __('Add to cart'),
                'message' => __($variation->stock_count.' items left on stock, please select less quantity')
            ]);
        }

        $wishlist_item->update([
            'cart_type' => 'cart',
            'product_id' => $wishlist_item->product_id,
            'variation_id' => $variation->id,
            'color_option_id' => isset($request['option']) && isset($request['option'][2])
                                    ? $request['option'][2]
                                    : null, //color
            'size_option_id' => isset($request['option']) && isset($request['option'][3])
                                    ? $request['option'][3]
                                    : null, //size
            'qty' => 1,
        ]);

        return response()->json([
            'status' => true,
            'wishlist_qty' => $this->cart->get_cart_items_qty('wishlist'),
            'cart_qty' => $this->cart->get_cart_items_qty('cart'),
            'message' => __('Product was moved to cart.'),
        ]);

    }

    public function cart_item_update($cart_id, $product_id)
    {

        $cart_item = $this->cart->where('id', $cart_id)
        ->where('cart_type', 'cart');

        if(auth()->guest()){
            $cart_item = $cart_item->where('session_id', $this->session_id)->first();
        }else{
            $cart_item = $cart_item->where('user_id', auth()->id())->first();
        }

        if(!$cart_item)
            return response()->json([
                'status' => false,
                'message' => __('Cart item not found.'),
            ]);

        $request_data = request()->all();
        $variation = $this->get_variation($request_data, $cart_item->product_id);

        if(!$variation)
            return response()->json([
                'status' => false,
                'message' => 'Please choose one of the options.'
            ]);

        $total_qty = $this->cart->where('product_id', $variation->product_id)->where('variation_id', $variation->id);

        if(auth()->guest()){
            $total_qty = $total_qty->where('session_id', $this->session_id)->count();
        }else{
            $total_qty = $total_qty->where('user_id', auth()->id())->count();
        }

        if($variation->stock_count < 1)
        {
            return response()->json([
                'status' => false,
                'btn_message' => __('Add to cart'),
                'message' => __('Sorry, :product_name is out of stock.', [
                    'product_name' => $variation->title
                ])
            ]);

        }else if($variation->stock_count <= intval($total_qty)){
            return response()->json([
                'status' => false,
                'btn_message' => __('Add to cart'),
                'message' => __($variation->stock_count.' items left on stock for :product_name, please select less quantity.', [
                    'product_name' => $variation->title
                ])
            ]);
        }

        $cart_item->update([
            'cart_type' => 'cart',
            'product_id' => $cart_item->product_id,
            'variation_id' => $variation->id,
            'qty' => 1,
        ]);

        return response()->json([
            'status' => true,
            'wishlist_qty' => $this->cart->get_cart_items_qty('wishlist'),
            'cart_qty' => $this->cart->get_cart_items_qty('cart'),
            'message' => __('Product was changed to :product_name.', [
                'product_name' => $variation->title
            ]),
        ]);

    }

    private function get_variations_options_grouped($id)
    {
        $variations_options_grouped = $this->productGroupOption_pivot
        ->distinct()
        ->select(
            'product_options.name as option_name',
            'product_options.value as option_value',
            'product_option_groups.name as group_name',
            'product_group_options.product_id',
            'product_group_options.product_option_id',
            'product_group_options.product_option_group_id'
        )
        ->join('product_option_groups', 'product_option_groups.id','=','product_group_options.product_option_group_id')
        ->join('product_options', 'product_options.id', '=', 'product_group_options.product_option_id')
        ->join('product_variation_options', 'product_options.id', '=', 'product_variation_options.product_option_id')

        ->where('product_group_options.product_id', $id)

        ->get()->groupBy('product_option_group_id')->toArray();
        return $variations_options_grouped;
    }

    private function get_variation($request, $product_id)
    {
    	if(isset($request['option']) && !empty($request['option'])){
	    	$groups = array_keys($request['option']);
	    	$options = array_values($request['option']);
	    	$group_ids = $groups;
	    	$option_ids = $options;
            // dd($group_ids, $option_ids, $product_id);
	    	$variation = $this->variation
            ->with(['inventories' => function($q){
                $q->whereHas('warehouse', function($q){
                    $q->where('status', 'active');
                } );
            }])
            ->select('variations.id', \DB::raw('COUNT(variation_id) as count'), 'variations.stock_count', 'variations.title')
	    	->distinct()
	    	->join('product_variation_options', 'product_variation_options.variation_id', '=', 'variations.id')
	    	->where('product_variation_options.product_id', $product_id)
	    	->whereIn('product_variation_options.product_option_group_id', $group_ids)
	    	->whereIn('product_variation_options.product_option_id', $option_ids)
            ->groupBy('variation_id')
            ->having(\DB::raw('COUNT(variation_id)'), ">", 1)
	    	->first();

            if($variation){
                $stock_sum = $variation->inventories->sum('quantity');
                $variation['stock_count'] = $stock_sum;
            }

    		return $variation;

            // select variations.id, variation_id, count(variation_id) count from `variations`

            // inner join `product_variation_options` on `product_variation_options`.`variation_id` = `variations`.`id`
            // where `product_variation_options`.`product_id` = 354
            // and `product_variation_options`.`product_option_group_id` in (2, 3)
            // and `product_variation_options`.`product_option_id` in (153, 220)
            // GROUP BY variation_id
            // HAVING
            // COUNT(variation_id) > 1
    	}
    	return false;
    }

    public function cart_items()
    {
        $cart_items = $this->cart->get_cart_variations();
        foreach ($cart_items as $key => $item) {
            $item->variation['option_groups'] = $this->variation->getRelatedVariationsOfColors($item->product->id);
        }

        $rule_discount = null;
        Discount::determineDiscountingBy($cart_items,$rule_discount);
        // dd($rule_discounts,$cart_items);

        $productCalculation = Cart::calcOrderWithWithoutCards($cart_items);

        $subtotal_price = $this->cart->calculateCartVariationsSubTotalPrice($cart_items);
        // dd($rule_discounts,$productCalculation,$subtotal_price);

        $html = view('layouts.components.parts.header.parts._cart_items', [
            'cart_items' => $cart_items,
            'subtotal_price' => $subtotal_price,
            'rule_discount' => $rule_discount,
            'productCalculation' => $productCalculation,
        ])->render();

        return response()->json(['status' => 'success', 'html' => $html, 'count' => $cart_items->count()]);
    }

    public function validate_discount_card(DiscountCardRequest $request, DiscountCard $discountCard_model)
    {
        // 333001662
        // 333-001-744
        // LT 00928
        $session_id = $this->cart->get_cookie();
        $user_id = auth()->check() ? auth()->id() : null;
        $card_number = $request->discount_card_number;

        try {
            $client = new \SoapClient("/TradeService/TradeService.svc?wsdl");

            $client_session_id = $client->StartSession(array('UserName'=>'', 'Password'=>'', 'DBName'=>''));

            try {
                $partner_data = $client->GetPartnerByCode(
                    array(
                        'sessionId' => $client_session_id->StartSessionResult,
                        'seqNumber' => 1,
                        'Code' => $card_number
                    )
                );
                if(isset($partner_data) && !empty($partner_data)){

                    $card_type = str_replace(' ', '', $partner_data->GetPartnerByCodeResult->Type);

                    try {
                        $getSalesAnalysisGroupedByPartner = $client->GetSalesAnalysisGroupedByPartner(
                            array(
                                'sessionId' => $client_session_id->StartSessionResult,
                                'seqNumber' => 2,
                                "DateBegin" => "2001-01-01",
                                "DateEnd" => date('Y-m-d'),
                                "PartnerCode" => $card_number,
                                "ShowNccVATCurSummsAndPrice" => true,
                                "ShowDiscoun" => true,
                                "ShowBonusPoints" => true,
                                "ShowVATSumms" => true
                            )
                        );
                        if(!empty($getSalesAnalysisGroupedByPartner)){

                            $lifetimeSales = $getSalesAnalysisGroupedByPartner->GetSalesAnalysisGroupedByPartnerResult->Rows->SalesAnalysisRowGroupedByPartner->VATSaleSumm;

                            $partnerLifetimeSales = intval($lifetimeSales);

                            switch ($card_type) {
                                case '333':
                                    $discount_percent = 20;
                                    break;
                                case '222':
                                    $discount_percent = $partnerLifetimeSales >= 1000000 ? 20 : 10;
                                    break;
                                case '111':
                                    $discount_percent = $partnerLifetimeSales >= 1000000 ? 20 : 10;
                                    break;
                                default:
                                    if($partnerLifetimeSales >= 300000 && $partnerLifetimeSales < 1000000){
                                        $discount_percent = 10;
                                    }elseif($partnerLifetimeSales >= 1000000){
                                        $discount_percent = 20;
                                    }else{
                                        $discount_percent = 0;
                                    }
                                break;
                            }

                            $discount_card_data = [
                                'code' => $partner_data->GetPartnerByCodeResult->Code,
                                'user_id' => $user_id == null ? '' : $user_id,
                                'session_id' => $session_id,
                                'discount' => $discount_percent,
                                'cardholder_name' => $partner_data->GetPartnerByCodeResult->Name,
                                'cardholder_phone' => $partner_data->GetPartnerByCodeResult->TaxCode,
                                'bonus' => $partner_data->GetPartnerByCodeResult->PartnerContracts->PartnerContract->Bonus,
                                'point' => $lifetimeSales,
                                'is_bonus_card' => $card_type != '333' ? 1 : 0,
                                'card_id' => $partner_data->GetPartnerByCodeResult->PartnerContracts->PartnerContract->DiscountCard,
                            ];

                            $discount_card = $discountCard_model->addOrUpdateDiscountCard($discount_card_data, $session_id, $user_id);

                            return response()->json([
                                'status' => true,
                                'message' => 'Card found',
                                'discount' => $discount_card->discount,
                                'cardholder_name' => $discount_card->cardholder_name,
                                'card_number' => $discount_card->code,
                            ]);

                        }
                    } catch (Exception $e) {
                        return response()->json(['status' => false, 'message' => __('Card not found') ]);
                    }
                }
                return response()->json(['status' => false, 'message' => __('Card not found') ]);
            } catch (Exception $e) {
                return response()->json(['status' => false, 'message' => __('Card not found') ]);
            }
        }
        catch (exception $e) {
            return response()->json(['error' => 'error', 'message' => __('Error with connection') ]);
        }
    }


   	// public function get_cart_items($cart_type = 'cart')
   	// {
   	// 	//check if exists user session cookie
   	// 	$session_id = $this->cart->get_cookie();

   	// 	//get user/guest cart items sum
   	// 	$cart_items = $this->cart->select('carts.id', 'carts.qty', 'carts.variation_id', 'products.id as product_id', 'products.title', 'products.slug', 'products.price as product_price', 'products.sale_price as product_sale_price', 'products.sku as product_sku', 'products.thumbnail as product_thumbnail', 'products.type as product_type','variations.price as variation_price', 'variations.sale_price as variations_sale_price', 'variations.thumbnail as variation_thumbnail', 'product_variation_options.product_option_group_id as option_group_id', 'product_variation_options.product_option_id as option_id', 'product_option_groups.name as option_group_name', 'product_option_groups.type as option_type', 'product_options.name as option_name', 'product_options.value as option_value')
   	// 	->join('products', 'products.id', '=', 'carts.product_id')
   	// 	->leftjoin('variations', 'variations.id', 'carts.variation_id')
   	// 	->leftjoin('product_variation_options', 'product_variation_options.variation_id', '=', 'carts.variation_id')
   	// 	->leftjoin('product_options', 'product_options.id', '=', 'product_variation_options.product_option_id')
   	// 	->leftjoin('product_option_groups', 'product_option_groups.id', '=', 'product_variation_options.product_option_group_id')
   	// 	->where('cart_type', $cart_type);

   	// 	// 095854045 Hasmik Ameria Bank
   	// 	if(auth()->check()){
   	// 	    $cart_items = $cart_items->where('carts.user_id', auth()->id())->get();
   	// 	}elseif($session_id){
   	// 	    $cart_items = $cart_items->where('carts.session_id', $session_id)->get();
   	// 	}else{
    //         $cart_items = null;
    //     }

   	// 	return $cart_items;
   	// }

    public function get_wishlist_items($cart_type = 'wishlist')
    {
        $cart_items = $this->cart->get_wishlist_variations($cart_type);

        foreach ($cart_items as $key => $item) {
            $item->variation['option_groups'] = $this->variation->getRelatedVariationsOfColors($item->product->id);
            // $item->variation['size_stock'] = $this->variation->getVariationSizesStock($item->variation);
        }
        // $subtotal_price = $this->cart->calculateCartSubTotalPrice($cart_items);

        $html = view('layouts.components.parts.header.parts._wishlist_items', [
            'cart_items' => $cart_items,
            // 'subtotal_price' => $subtotal_price
        ])->render();
        return response()->json(['status' => 'success', 'html' => $html, 'count' => $cart_items->count()]);

        // // //check if exists user session cookie
        // $session_id = $this->cart->get_cookie();

        // $cart_items = $this->product
        // ->select('products.id', 'products.title', 'products.thumbnail', 'products.slug', 'products.price', 'products.sale_price', 'products.order', 'carts.qty as in_wishlist', 'brands.slug as brand_slug', 'brands.title as brand_name',
        //     \DB::raw("MIN(variations.price) AS variation_price"),
        //     \DB::raw("MIN(variations.sale_price) AS variation_sale_price")
        // )
        // ->leftjoin('variations', 'products.id', '=', 'variations.product_id')
        // ->join('carts', 'carts.product_id', '=', 'products.id')
        // ->join('brands', 'brands.id', '=', 'products.brand_id')
        // ->where('products.status', 'published')
        // // ->where('products.lang', $this->lang)
        // ->where('carts.cart_type', $cart_type)
        // ->orderBy('order', 'DESC');

        // // 095854045 Hasmik Ameria Bank
        // if(auth()->check()){
        //     $cart_items = $cart_items->where('carts.user_id', auth()->id())
        //     ->groupBy('products.id')
        //     ->paginate(9);
        // }elseif($session_id){
        //     $cart_items = $cart_items->where('carts.session_id', $session_id)
        //     ->groupBy('products.id')
        //     ->paginate(9);
        // }else{
        //     $cart_items = null;
        // }
        // return $cart_items;
    }

    public function getDeliveryDaysByAddress(Request $request){
        $user_address_id = $request->has('user_address_id')
            ? $request->get('user_address_id')
            : null;

        $delivery_address_id = $request->has('delivery_address_id')
            ? $request->get('delivery_address_id')
            : null;
        if($user_address_id){
            return response()->json(
                $this->getDeliveryDaysByUserAddress($user_address_id)
            );
        }elseif($delivery_address_id){
            return response()->json(
                $this->getDeliveryDaysByDeliveryAddressId($delivery_address_id)
            );
        }
        return response()->json([
            'status' => false,
        ], 400);
    }

    private function getDeliveryDaysByUserAddress($address_id){

        $address = UserAddress::find($address_id);
        if($address && $address->city_code){
            $devlidery_address = DeliveryAddresses::where('city_code', $address->city_code)->first();
            if($devlidery_address){
                $min_day = $devlidery_address->min;
                $max_day = $devlidery_address->max;
                return [
                    'status' => true,
                    'html' => $min_day == $max_day
                        ? $min_day. ($min_day == 1 ? ' день' : ' дня')
                        : $min_day.'/'.$max_day.' дня',
                    'price' => number_format($devlidery_address->price, 0,0, ''). ' ₽',
                    'min' => $devlidery_address->min,
                    'max' => $devlidery_address->max
                ];
            }
        }
        return [
            'status' => false,
        ];
    }

    private function getDeliveryDaysByDeliveryAddressId($address_id){
        $devlidery_address = DeliveryAddresses::find($address_id);
        if($devlidery_address){
            $min_day = $devlidery_address->min;
            $max_day = $devlidery_address->max;
            return [
                'status' => true,
                'html' => $min_day == $max_day
                    ? $min_day. ($min_day == 1 ? ' день' : ' дня')
                    : $min_day.'/'.$max_day.' дня',
                'price' => number_format($devlidery_address->price, 0,0, ''). ' ₽',
                'min' => $devlidery_address->min,
                'max' => $devlidery_address->max
            ];
        }
        return [
            'status' => false,
        ];
    }

   	public function remove($id)
   	{
   		$item = $this->cart->where('id', $id);
   		if(auth()->guest()){
   			$item = $item->where('session_id', get_user_session_id())->first();
   		}else{
   			$item = $item->where('user_id', auth()->id())->first();
   		}

   		if($item)
   		{
            $cart_type = $item->cart_type;
            $color_option_id = $item->color_option_id;
   			$item->delete();

            $count = $this->cart;
            if(auth()->guest()){
                $count = $count->where('session_id', get_user_session_id());
            }else{
                $count = $count->where('user_id', auth()->id());
            }
            $count = $count->where('cart_type', $cart_type)->count();

            if(request()->ajax()){
                return response()->json([
                    'status' => true,
                    'count' => $count,
                    'empty_messgae' => $count == 0
                                            ? view('cart.parts._empty')->render()
                                            : null,
                    'action_from'  => request()->has('action-from') ? request()->get('action-from') : null,
                    'message' => __('Removed.'),
                    'button' => '<button type="submit" class="main-btn uppercase" data-action="add-to-cart" data-color-id="'.$color_option_id.'">'. __('Добавить') .'</button>',
                ]);
            }
            
   			return redirect()->back()->with('success', 'Product was removed from your cart.');
   		}
        if(request()->ajax()){
            return response()->json([
                'status' => false,
                'message' => __('Not Found.'),
            ]);
        }
   		return redirect()->back()->with('error', 'Product not found.');
   	}

}

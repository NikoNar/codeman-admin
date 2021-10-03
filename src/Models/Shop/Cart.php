<?php

namespace Codeman\Admin\Models\Shop;

use App\Models\DeliveryAddresses;
use Illuminate\Database\Eloquent\Model;
use Codeman\Admin\Models\Shop\Variation;
use Illuminate\Support\Facades\Session;
use App\Models\UserDiscountCard;
use Codeman\Admin\Models\Shop\DiscountCard;
use App\Logics\Discount;

use function PHPUnit\Framework\isEmpty;

class Cart extends Model
{
    protected $fillable = [
    	'user_id',
    	'session_id',
    	'product_id',
    	'variation_id',
        'color_option_id',
        'size_option_id',
    	'qty',
        'status',
    	'cart_type',
    ];

    public function product()
    {
    	return $this->belongsTo('\Codeman\Admin\Models\Shop\Product');
    }

    public function variation()
    {
        return $this->belongsTo('\Codeman\Admin\Models\Shop\Variation');
    }

    //Calculate the price of all products on user cart without including additional fees. Just a SUM of all products
    public static function calculateCartSubTotalPrice($items = null, $discount_percent = 0)
    {
        $total = 0;

        if($items && !empty($items)){
            foreach ($items as $key => $item) {
                $item_price = (new self)->calculateCartItemPrice($item, $discount_percent); // item price * qty
                $total = $total + $item_price;
            }
        }
        return $total;
        // return number_format($total, 0);
    }

    //Calculate the price of all products on user cart without including additional fees. Just a SUM of all products
    public static function calculateCartVariationsSubTotalPrice($items = null, $discount_percent = 0)
    {
        $total = [
            'regular_price' => 0,
            'sale_price' => 0,
            'diff' => 0,
        ];

        if($items && !empty($items)){
            foreach ($items as $key => $item) {
                $item_price = (new self)->calculateCartItemVariationPrice($item, $discount_percent); // item price * qty
                $total = (new self)->sumCartItemPrices($item_price, $total);
            }
        }
        // dd($total);
        return $total;
    }

    private function sumCartItemPrices($item_price, &$total)
    {
        $total['regular_price'] += $item_price['regular_price'];
        $total['sale_price'] += $item_price['sale_price'] ?  $item_price['sale_price'] : $item_price['regular_price'];
        $total['diff'] = $item_price['diff'] ? $total['diff'] + $item_price['diff'] : $total['diff'];

        return $total;
    }

    public function calculateCartItemPrice($item, $discount_percent = 0)
    {
        // product_sale_percent
        // variation_sale_percent
        $discount_percent;
        $price_of_one_piece = 0;
        $product_discount_percent = $item->variation_sale_percent > 0 ? $item->variation_sale_percent : $item->product_sale_percent;
        if($item->variation_price > 0)
        {
            if($item->variations_sale_price > 0)
            {
                if($discount_percent <= $product_discount_percent){
                    $price_of_one_piece = $price_of_one_piece + $item->variations_sale_price;
                }else{
                    $price_of_one_piece = $price_of_one_piece + ($item->variation_price - $item->variation_price*$discount_percent/100);
                }
            }
            else
            {
                if($discount_percent > 0 ){
                    $price_of_one_piece = $price_of_one_piece + ($item->variation_price - $item->variation_price*$discount_percent/100);
                }else{
                    $price_of_one_piece = $price_of_one_piece + $item->variation_price;
                }
            }
        }
        else if($item->product_price > 0){
            if($item->product_sale_price > 0){
                if($discount_percent <= $product_discount_percent){
                    $price_of_one_piece = $price_of_one_piece + $item->product_sale_price;
                }else{
                    $price_of_one_piece = $price_of_one_piece + ($item->product_price - $item->product_price*$discount_percent/100);
                }
            }
            else{
                if($discount_percent > 0 ){
                    $price_of_one_piece = $price_of_one_piece + ($item->product_price - $item->product_price*$discount_percent/100);
                }else{
                    $price_of_one_piece = $price_of_one_piece + $item->product_price;
                }

            }
        }
        return $price_of_one_piece*$item->qty;
    }

    public function calculateCartItemVariationPrice($item, $discount_percent = 0)
    {
        $product_discount_percent = 0;
        $price_of_one_piece = 0;
        $regular_price = 0;
        $sale_price = 0;
        $sale_price_diff = 0;
        $discount_card_amount = 0;

        if($item->variation->price > 0){
            $regular_price = $item->variation->price;
            if($item->variation->sale_price > 0){
                if($discount_percent <= $product_discount_percent){
                    $sale_price = $item->variation->sale_price;
                }else{
                    $sale_price = $item->variation->price - $item->variation->price*$discount_percent/100;
                }
            }
            else{
                if($discount_percent > 0 ){
                    $sale_price =  $item->variation->price - $item->variation->price*$discount_percent/100;
                }

            }
        }

        return [
            'regular_price' => $regular_price,
            'sale_price' => $sale_price > 0 ? $sale_price : null,
            'diff' => $sale_price > 0 ? $regular_price - $sale_price : null,
        ];
    }

    public function calculateCartItemRegularPriceSum($item, $discount_percent = 0)
    {
        $price_of_one_piece = 0;
        $product_discount_percent = $item->variation_sale_percent > 0 ? $item->variation_sale_percent : $item->product_sale_percent;
        if($item->variation_price > 0)
        {
            $price_of_one_piece = $price_of_one_piece + $item->variation_price;

        }
        else if($item->product_price > 0){
            $price_of_one_piece = $price_of_one_piece + $item->product_price;
        }
        return $price_of_one_piece*$item->qty;
    }

    public function get_cart_items_qty($cart_type = 'cart')
    {
        //check if exists user session cookie
        $session_id = $this->get_cookie();
        //get user/guest cart items sum
        $qty = 0;
        if(auth()->check()){
            $qty = self::where('user_id', auth()->id())->where('cart_type', $cart_type)->sum('qty');
        }elseif($session_id){
            $qty = self::where('session_id', $session_id)->where('cart_type', $cart_type)->sum('qty');
        }

        return $qty;
    }

    public function get_cart_items($cart_type = 'cart', $user_id = null, $session_id = null )
    {
        //check if exists user session cookie
        $session_id = $user_id ? null : get_user_session_id();

        //get user/guest cart items sum
        $cart_items = self::select('carts.id', 'carts.qty', 'carts.variation_id', 'products.id as product_id', 'products.title', 'products.slug', 'products.price as product_price', 'products.sale_price as product_sale_price', 'products.sale_percent as product_sale_percent', 'products.sku as product_sku', 'products.thumbnail as product_thumbnail', 'products.type as product_type','variations.price as variation_price', 'variations.sale_price as variations_sale_price', 'variations.sale_percent as variation_sale_percent', 'variations.thumbnail as variation_thumbnail', 'product_variation_options.product_option_group_id as option_group_id', 'product_variation_options.product_option_id as option_id', 'product_option_groups.name as option_group_name', 'product_option_groups.type as option_type', 'product_options.name as option_name', 'product_options.value as option_value', 'products.stock_count as product_stock_count', 'variations.stock_count as variations_stock_count', 'carts.created_at')
        ->join('products', 'products.id', '=', 'carts.product_id')
        ->leftjoin('variations', 'variations.id', 'carts.variation_id')
        ->leftjoin('product_variation_options', 'product_variation_options.variation_id', '=', 'carts.variation_id')
        ->leftjoin('product_options', 'product_options.id', '=', 'product_variation_options.product_option_id')
        ->leftjoin('product_option_groups', 'product_option_groups.id', '=', 'product_variation_options.product_option_group_id')
        ->where('cart_type', $cart_type);

        if(auth()->check() && !$user_id){
            $cart_items = $cart_items->where('carts.user_id', auth()->id())->get();
        }elseif($user_id){
            $cart_items = $cart_items->where('carts.user_id', $user_id)->get();
        }elseif($session_id){
            $cart_items = $cart_items->where('carts.session_id', $session_id)->get();
        }else{
            $cart_items = null;
        }

        $outofstock_products = array();
        if(!$cart_items->isEmpty())
        {
            foreach ($cart_items as $key => $item) {

                if($item->variation_id != null)
                { //checking if product has variation
                    if($item->variations_stock_count == 0)
                    { // Product variation out of stock, removing product from cart
                        $outofstock_products[] = $item;
                        $item->delete();
                        $cart_items->forget($key);
                    }else if($item->qty > $item->variations_stock_count)
                    { //Product variation stock qty less than qty on user cart, decreasing cart to be equal stock qty.
                        $item->update(['qty' => $item->variations_stock_count]);
                    }
                }else{ // if product is not a variation
                    if($item->product_stock_count == 0){ // Product out of stock, removing product from cart
                        $outofstock_products[] = $item;
                        $item->delete();
                        $cart_items->forget($key);
                    }else if($item->qty > $item->product_stock_count) { //Product stock qty less than qty on user cart, decreasing cart to be equal stock qty.
                        $item->update(['qty' => $item->product_stock_count]);
                    }
                }
            }
        }
        return $cart_items;
    }

    public function get_cart_variations($cart_type = 'cart', $user_id = null, $session_id = null )
    {
        $variation_model = new Variation;
        //check if exists user session cookie
        $session_id = $user_id ? null : get_user_session_id();

        //get user/guest cart items sum
        $cart_items = self::with([ 'variation' => function($q){
            $q->with([
                'options',
                'product' => function($q){
                    $q->with('categories');
                },
                'inventories' => function($q){
                    $q->whereHas('warehouse', function($q){
                        $q->where('status', 'active');
                    } );
                }
            ]);
        }])
        ->where('cart_type', $cart_type)
        ->orderBy('product_id','DESC')->orderBy('variation_id', 'DESC')->orderBy('created_at', 'DESC');


        if(auth()->check() && !$user_id){
            // $check_editable = Cart::where([['user_id',auth()->id()], ['status',1] ])->first();
            // if($check_editable)
            // {
                $cart_items = $cart_items->where([ ['carts.user_id', auth()->id()], ['status',1] ])->get();
            // }else{
            //     $cart_items = $cart_items->where([ ['carts.user_id', auth()->id()],['status',0] ])->get();
            // }
        }elseif($user_id){
            $cart_items = $cart_items->where('carts.user_id', $user_id)->get();
        }elseif($session_id){
            $cart_items = $cart_items->where('carts.session_id', $session_id)->get();
        }else{
            $cart_items = null;
        }

        $outofstock_products = array();
        if(!$cart_items->isEmpty())
        {
            $product_ids = $cart_items->pluck('product_id')->toArray();
            $variation_options_grouped = $variation_model->getVariationsOptionsGrouped($product_ids, [2,3], 'product_id');
            foreach ($cart_items as $key => $item) {

                $stock_sum = $item->variation->inventories->sum('quantity');
                $item->setAttribute('total_stock', $stock_sum);
                // $item->variation['sizes'] = $this->getVartaionAllSizes($item->variation);
                $item->variation['option_groups'] = isset($variation_options_grouped[$item->product_id]) ? $variation_options_grouped[$item->product_id] : null;
                if($stock_sum == 0)
                {
                    // Product variation out of stock, removing product from cart
                    array_push($outofstock_products,$item);
                    $item->delete();
                    $cart_items->forget($key);
                }else if($item->qty > $stock_sum) {
                    //Product variation stock qty less than qty on user cart, decreasing cart to be equal stock qty.
                    $item->update(['qty' => $item->stock_sum]);
                }
            }
        }
        Session::put('outofstock_products', $outofstock_products);
        return $cart_items;
    }

    public function get_wishlist_variations($cart_type = 'wishlist', $user_id = null, $session_id = null )
    {
        $variation_model = new Variation;
        //check if exists user session cookie
        $session_id = $user_id ? null : get_user_session_id();

        //get user/guest cart items sum
        $cart_items = self::with([ 'variation' => function($q){
            $q->with(['options', 'product']);
        }])
        ->where('cart_type', $cart_type);

        if(auth()->check() && !$user_id){
            $cart_items = $cart_items->where('carts.user_id', auth()->id())->get();
        }elseif($user_id){
            $cart_items = $cart_items->where('carts.user_id', $user_id)->get();
        }elseif($session_id){
            $cart_items = $cart_items->where('carts.session_id', $session_id)->get();
        }else{
            $cart_items = null;
        }

        $outofstock_products = array();
        if(!$cart_items->isEmpty())
        {
            $product_ids = $cart_items->pluck('product_id')->toArray();
            $variation_options_grouped = $variation_model->getVariationsOptionsGrouped($product_ids, [2,3], 'product_id');
            foreach ($cart_items as $key => $item) {
                // $item->variation['sizes'] = $this->getVartaionAllSizes($item->variation);
                // $item->variation['colors'] = $this->getVartaionAllColors($item->variation);
                $item->variation['option_groups'] = isset($variation_options_grouped[$item->product_id]) ? $variation_options_grouped[$item->product_id] : null;
                if($item->variation->stock_count == 0)
                {
                    // Product variation out of stock, removing product from cart
                    $outofstock_products[] = $item;
                }
            }
        }
        return $cart_items;
    }

    private function getVartaionAllSizes($variation)
    {
        $color_sizes_variation_ids = ProductVariationOption::select('variation_id')
            ->where('product_id', $variation->product->id)
            ->where('product_option_group_id', 2)
            ->where('product_option_id', $variation->options[0]->pivot->product_option_id)
            ->get()
            ->pluck('variation_id')
            ->toArray();
        $sizes = ProductOption::
            select('product_option_groups.name as group_name', 'product_option_groups.id as group_id', 'product_option_groups.type as group_type', 'product_options.name as option_name', 'product_options.value as option_value', 'product_options.id as option_id')->
            join('product_variation_options', 'product_variation_options.product_option_id', 'product_options.id')->
            join('product_option_groups', 'product_variation_options.product_option_group_id', 'product_option_groups.id')
            ->whereIn('product_variation_options.variation_id', $color_sizes_variation_ids)
            ->where('product_option_groups.id', '3')
            ->orderBy('option_name', 'ASC')
            ->get();
        return $sizes;
    }

    private function getVartaionAllColors($variation)
    {
        $colors_variation_ids = ProductVariationOption::select('variation_id')
        ->where('product_id', $variation->product->id)
        ->where('product_option_group_id', 2)
        ->groupBy('product_option_id')
        ->get()
        ->pluck('variation_id')
        ->toArray();

        $colors = ProductOption::
        select('product_option_groups.name as group_name', 'product_option_groups.id as group_id', 'product_option_groups.type as group_type', 'product_options.name as option_name', 'product_options.value as option_value', 'product_options.id as option_id', 'variation_id')->
        join('product_variation_options', 'product_variation_options.product_option_id', 'product_options.id')->
        join('product_option_groups', 'product_variation_options.product_option_group_id', 'product_option_groups.id')
        ->whereIn('product_variation_options.variation_id', $colors_variation_ids)
        ->where('product_option_groups.id', '2')
        ->orderBy('option_name', 'ASC')
        ->get();

        return $colors;
    }

    public function clear_user_cаrt()
    {
        //check if exists user session cookie
        $session_id = $this->get_cookie();

        $cart_items = self::select('id')->where('cart_type', 'cart');

        if(auth()->check()){
            $cart_items = $cart_items->where([ ['user_id', auth()->id()], ['status',1]])->delete();
            Cart::where([ ['user_id',auth()->id()], ['status',0] ])->update(['status'=> 1]);
        }elseif($session_id){
            $cart_items = $cart_items->where('session_id', $session_id)->delete();
        }
        return true;
    }

    public function get_cookie()
    {
        $cookie = \Cookie::get('user_session_id');

        if($cookie){
            return  $cookie;
        }

        $uuid = \Str::orderedUuid()->toString();
        // $session_id = \Str::random(32);
        $one_year = time() + 60 * 60 * 24 * 365;// one year
        \Cookie::queue('user_session_id', $uuid, $one_year ); // change $session_id with uuid

        return $uuid;
    }

    public static function attach_carts_to_newly_reg_user($user_session_id,$user_id)
    {
        self::where('session_id',$user_session_id)->update([
            'user_id' => $user_id,
        ]);
    }

    public static function attach_carts_to_user_id()
    {
        $user_session_id = get_user_session_id();
        $carts = self::where([ ['session_id',$user_session_id] , ['user_id',null] ])->get();
        foreach($carts as $cart)
        {
            self::where('id',$cart->id)->update([
                'user_id' => auth()->id()
            ]);
        }
    }

    public function recalculate_cart($options = [])
    {
        $session_id =  (new self)->get_cookie();
        $cart_products = (new  self)->get_cart_variations();

        $subtotal_price = (new self)->calculateCartVariationsSubTotalPrice($cart_products, 0);
        $delivery_price = isset($options['delivery_option_price']) ? $options['delivery_option_price'] : null;

        if($delivery_price !== null){
            $subtotal_price['regular_price'] = $subtotal_price['regular_price'] + $delivery_price;
            $subtotal_price['sale_price'] = $subtotal_price['sale_price'] + $delivery_price;
        }

        $rule_discount = null;
        Discount::determineDiscountingBy($cart_products,$rule_discount);
        $productCalculation = self::calcOrderWithWithoutCards($cart_products);

        $order_total_widget = view('cart.parts._order_total_info',
            compact('rule_discount','productCalculation','subtotal_price', 'cart_products', 'delivery_price')
        )->render();

        return [
            'html' => [
                'cart_total_widget' => $order_total_widget,
            ],
        ];
    }

    public function getDeliveryOptionPriceByCityCodeOrUserAddressId($user_shipping_address_id = null, $user_entered_city_id = null){

        if($user_shipping_address_id){
            $user_address = UserAddress::find($user_shipping_address_id);

            if(!$user_address || !$user_address->city_code){
                return false;
            }

            $delivery_address = DeliveryAddresses::where('city_code', $user_address->city_code)->first();
            if(!$delivery_address){
                return false;
            }
            return $delivery_address->price;
        }else if ($user_entered_city_id){
            $delivery_address = DeliveryAddresses::find($user_entered_city_id);
            if(!$delivery_address){
                return false;
            }
            return $delivery_address->price;
        }
        return false;
    }
    //method for calculating already discounted order cards price_s(discounted,non-discounted)
    public static function calcOrderWithWithoutCards($carts)
    {

        $regular_price = 0;
        $discountByShop_price = 0;
        $discountByLoyalityCard_price = 0;
        $discountByRules = 0;
        $discountedPrice = 0;
        $total_discount = 0;
        foreach($carts as $cart)
        {
            $regular_price += $cart->REGULAR_PRICE;

            if(isset($cart->DISCOUNT_BY_CART_RULES))
            {
                $last_rule = count($cart->DISCOUNT_BY_CART_RULES);
                $discountByRules += $cart->DISCOUNT_BY_CART_RULES[$last_rule-1]['discounted__price'];
                $tempDiscounted = $cart->DISCOUNT_BY_CART_RULES[$last_rule-1]['discounted__price'];
            }
            else if(isset($cart->DISCOUNT_BY_LOYALITY_CARD))
            {
                $discountByLoyalityCard_price += $cart->DISCOUNT_BY_LOYALITY_CARD['disocunt_diff_in_price'];
                $tempDiscounted = ($cart->DISCOUNT_BY_LOYALITY_CARD['price_with_this_discount'] != null) ? $cart->DISCOUNT_BY_LOYALITY_CARD['price_with_this_discount'] : $cart->REGULAR_PRICE;
            }
            else if(isset($cart->DISCOUNT_BY_SHOP) && $cart->DISCOUNT_BY_SHOP)
            {
                $discountByShop_price += $cart->DISCOUNT_BY_SHOP['disocunt_diff_in_price'];
                $tempDiscounted = ($cart->DISCOUNT_BY_SHOP['price_with_this_discount'] != null) ? $cart->DISCOUNT_BY_SHOP['price_with_this_discount'] : $cart->REGULAR_PRICE;
            }else{
                $tempDiscounted = $cart->variation->price;
            }


            // //TRSSSSSSSS
            // $regular_price += $cart->REGULAR_PRICE;

            // $tempDiscounted = 0;
            // if(isset($cart->DISCOUNT_BY_SHOP) && $cart->DISCOUNT_BY_SHOP)
            // {
            //     $discountByShop_price += $cart->DISCOUNT_BY_SHOP['disocunt_diff_in_price'];
            //     $tempDiscounted = ($cart->DISCOUNT_BY_SHOP['price_with_this_discount'] != null) ? $cart->DISCOUNT_BY_SHOP['price_with_this_discount'] : $cart->REGULAR_PRICE;
            // }else{
            //     //disocunt dosen't exists
            //     $tempDiscounted = $cart->variation->price;
            // }

            // if(isset($cart->DISCOUNT_BY_LOYALITY_CARD))
            // {
            //     $discountByLoyalityCard_price += $cart->DISCOUNT_BY_LOYALITY_CARD['disocunt_diff_in_price'];
            //     $tempDiscounted = ($cart->DISCOUNT_BY_LOYALITY_CARD['price_with_this_discount'] != null) ? $cart->DISCOUNT_BY_LOYALITY_CARD['price_with_this_discount'] : $cart->REGULAR_PRICE;
            // }

            $discountedPrice += $tempDiscounted;
        }


        $total_discount = $discountByRules + $discountByShop_price + $discountByLoyalityCard_price;
        // dd($total_discount);
        // dd($discountByRules,$regular_price,$total_discount,$carts,$discountByShop_price,$discountedPrice);
        return [
            'discounted_price' => $discountedPrice,
            'regular_price' => $regular_price,
            'discount_by_shop_price' => $discountByShop_price,
            'discount_by_loyality_card_price' => $discountByLoyalityCard_price,
            'discount_by_rules' => $discountByRules,
            'total_discount' => $total_discount
        ];
    }


}

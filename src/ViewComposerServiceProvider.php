<?php

namespace App\Providers;

use App\Models\Cart;
use App\Models\Product;
use App\Models\Variation;
use App\Models\ProductGroupOption;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider
{

    public function __construct()
    {

    }

    public function boot(Cart $cart_model)
    {
        View::composer('layouts.components._header', function ($view) use ($cart_model) {
            //get user/guest cart items sum
            $cart_items_sum = $cart_model->get_cart_items_qty('cart');
            //get user/guest wishlist items sum
            $wishlist_items_sum = $cart_model->get_cart_items_qty('wishlist');
            // dd($cart_items_sum);
            $view->with('cart_items_sum', $cart_items_sum);
            $view->with('wishlist_items_sum', $wishlist_items_sum);
        });
    }


    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        // TODO: Implement register() method.
    }
}
<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class FeedProduct extends Model
{
    protected $fillable = [
    	'part_number', 'name', 'brand_name','merchant_name', 'description', 'image', 'upc', 'product_url', 'retail_price', 'sale_price', 'final_price', 
        'onSale_flag', 'google_categories', 'merchant_category','merchant_category', 'shipping_charge', 'shipping_charge_1', 'shipping_charge_2', 
        'shipping_charge_3', 'shipping_charge_4', 'shipping_charge_5', 'shipping_charge_6', 'free_shipping_minimum', 'inventory', 'shipping_countries',
        'shipping_information',  'tax',  'tax_country', 'local_currency', 'converter_currency', 'converter_price', 'approved'
    ];
     
     public function variations()
    {
        return $this->hasMany('Codeman\Admin\Models\Variation',  'product_part_number', 'part_number');
    }

     public function color_variation() {
        return $this->variations()->groupBy('color');
    }
}

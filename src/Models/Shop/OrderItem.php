<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable = [
    	'order_id',
    	'product_id',
    	'title',
    	'variation_id',
    	'price',
    	'sale_price',
    	'qty',
        'discount_history',
    	'variation_option_type',
    	'variation_option_group',
    	'variation_option_value',
    ];

    public function productmetas()
    {
        return $this->hasMany('\Codeman\Admin\Models\Shop\ProductMeta','product_id','product_id');
    }

    public function product()
    {
        return $this->belongsTo('\Codeman\Admin\Models\Shop\Product');
    }

    public function variation()
    {
        return $this->belongsTo('\Codeman\Admin\Models\Shop\Variation');
    }

    public function order()
    {
        return $this->belongsTo('\Codeman\Admin\Models\Shop\Order');
    }

}

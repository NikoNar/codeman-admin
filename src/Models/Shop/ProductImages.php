<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ProductImages extends Model
{
    protected $fillable = [
    	'product_id',
    	'variation_id',
    	'url',
    	'alt',
    	'sort'
    ];
}

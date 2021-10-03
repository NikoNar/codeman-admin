<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ProductGroupOption extends Model
{
    protected $fillable = [
    	'product_id',
    	'product_option_id',
    	'product_option_group_id',
    ];
}

<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ProductVariationOption extends Model
{
	public $timestamps = false;
	
   	protected $fillable = [
   		'variation_id',
   		'product_option_group_id',
   		'product_option_id',
   		'product_id',
   	];

   	public function variation()
   	{
   		return $this->belongsTo('Codeman\Admin\Models\Shop\Variation');
   	}

	public function product()
	{
		return $this->belongsTo('Codeman\Admin\Models\Shop\Product');
	}

   	public function optionGroup()
   	{
   		return $this->belongsTo('Codeman\Admin\Models\Shop\ProductOptionGroup');
   	}
}

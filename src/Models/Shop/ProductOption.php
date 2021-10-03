<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ProductOption extends Model
{
    protected $fillable = [
        'api_id',
    	'product_option_group_id',
    	'value',
    	'name',
    	'status',
    	'lang',
    	'parent_lang_id',
    	'order'
    ];

    public function getSearchableFields()
    {
        return [
            'value',
            'name',
            'status',
            'created_at'
        ];
    }

    public function productOptionGroup()
    {
    	return $this->belongsTo('\Codeman\Admin\Models\Shop\ProductOptionGroup');
    }
}

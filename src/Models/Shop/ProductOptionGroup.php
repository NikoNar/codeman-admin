<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class ProductOptionGroup extends Model
{
    protected $fillable = [
    	'name',
    	'type',
    	'status',
        'show_on_website',
    	'lang',
    	'parent_lang_id',
    	'order'
    ];

    public function productOptions()
    {
    	return $this->hasMany('\Codeman\Admin\Models\Shop\ProductOption');
    }
}

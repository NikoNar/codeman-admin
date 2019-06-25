<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
    	'parent_id',
    	'lang',
    	'parent_lang_id',
    	'title',
    	'slug',
    	'status',
    	'description',
    	'short_description',
    	'tips_for_using',
    	'how_the_tool_works',
    	'awards_and_prizes',
    	'thumbnail',
    	'gallery',
    	'product_type',
    	'weight',
    	'length',
    	'width',
    	'height',
    	'regular_price',
    	'sale_price',
    	'in_stock',
    	'order',
    	'meta-title',
    	'meta-description',
    	'meta-keywords',
        'variations'
    ];



    // public function categories()
    // {
    //     return $this->belongsToMany('Codeman\Admin\Models\Category', 'product_categories', 'product_id', 'category_id');
    // }

    
     /**
     * Get all of the categories for the post.
     */
    public function categories()
    {
        return $this->morphToMany('Codeman\Admin\Models\Category', 'categorisable');
    }

    public function singleCategory()
    {
        return $this->belongsToMany('Codeman\Admin\Models\Category', 'product_categories', 'product_id', 'category_id')->first();
    }

    public function singleProductcategories()
    {
        return $this->hasMany('Codeman\Admin\Models\ProductCategory');
    }
}

<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id','parent_id', 'title', 'content', 'type', 'lang', 'slug', 'parent_lang_id',  'order', 'thumbnail', 'level', 'node', 'status', 'api_cat_id'];
    protected $table = "cm_categories";

    public function getSearchableFields()
    {
        return [
            'title',
            'content',
            'type', 
            'api_cat_id',
            'status',
            'created_at'
        ];
    }

    /**
    	* Get the categoru childs.
    	*
    	* @return string
   	*/
   	public function childs()
    {
        return $this->hasMany('Codeman\Admin\Models\Category','parent_id','id')
        ->select('title', 'slug', 'id', 'order')->orderBy('order', 'DESC');
   	}

    public function catChilds($id = null)
    {
        return $this->hasMany('Codeman\Admin\Models\Category','parent_id','id')
        ->where('status','published')->orderBy('order', 'DESC');
    }

    public function relatedResources()
    {

        return $this->morphedByMany('Codeman\Admin\Models\Resource', 'categorisable')
        ->with('metas');
    }

    public function products()
    {
        return $this->morphedByMany('App\Models\Product','categorisable');
    }

    public function shop_products()
    {
        return $this->morphedByMany('Codeman\Admin\Models\Shop\Product','categorisable');
    }

    // public function news()
    // {
    //   return $this->belongsToMany('Codeman\Admin\Models\News','news_categories');
    // }

    /**
     * Get all of the news that are assigned this category.
     */
    // public function news()
    // {
    //     return $this->morphedByMany('Codeman\Admin\Models\News', 'categorisable');
    // }


    /**
     * Get all of the products that are assigned this category.
     */
    // public function products()
    // {
    //     return $this->morphedByMany('Codeman\Admin\Models\Product', 'categorisable');
    // }


    public function lecturers()
    {
        return $this->morphedByMany('Codeman\Admin\Models\Lecturer', 'categorisable');
    }

    public function portfolios()
    {
        return $this->morphedByMany('Codeman\Admin\Models\Portfolio', 'categorisable');
    }

    public function files()
    {
        return $this->morphedByMany('Codeman\Admin\Models\File', 'categorisable');
    }

    public static function getProductsCategories($product_ids = null, $lang = null)
    {
        if(!$lang){
            $lang = \App::getLocale();
        }
        // Get Categories For Filter
        $categories = self::distinct()
        ->select('cm_categories.id', 'cm_categories.title', 'cm_categories.slug', 'cm_categories.level', 'cm_categories.node', 'cm_categories.order', 'cm_categories.parent_id')
        ->join('categorisables','categorisables.category_id', 'cm_categories.id')
        ->where('cm_categories.type', 'products')
        ->where('cm_categories.lang', $lang)
        ->where('status', 'published');

        if($product_ids){
            $categories =  $categories->whereIn('categorisables.categorisable_id', $product_ids);
        }
        $categories =  $categories->orderBy('level', 'ASC')
        ->orderBy('order', 'DESC')
        ->get();
        // ->groupBy('parent_id');
        return $categories;
    }

}


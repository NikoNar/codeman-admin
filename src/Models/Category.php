<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = ['id','parent_id', 'title', 'content', 'type', 'lang', 'slug', 'parent_lang_id',  'order', 'thumbnail', 'level', 'node', 'status'];
    protected $table = "cm_categories";

    /**
    	* Get the categoru childs.
    	*
    	* @return string
   	*/
   	public function childs()
    {
        return $this->hasMany('Codeman\Admin\Models\Category','parent_id','id')->select('title', 'slug', 'id', 'order')->orderBy('order', 'DESC');
   	}

    public function catChilds($id = null)
    {
        return $this->hasMany('Codeman\Admin\Models\Category','parent_id','id')->where('status','published')->orderBy('order', 'DESC');
    }

    public function relatedResources()
    {

        return $this->morphedByMany('Codeman\Admin\Models\Resource', 'categorisable')->with('metas');
    }


    public function offers()
    {
        return $this->morphedByMany('App\Offer', 'categorisable');
    }
    // public function products()
    // {
    //   return $this->belongsToMany('Codeman\Admin\Models\Product','product_categories');
    // }

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

}


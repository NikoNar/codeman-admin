<?php

namespace Codeman\Admin\Models;

use Codeman\Admin\Models\BaseModel;

// use Illuminate\Database\Eloquent\Model;

class News extends BaseModel
{
    protected $fillable = ['lang', 'parent_lang_id', 'title', 'slug', 'status', 'description', 'thumbnail', 'meta-title', 'meta-description', 'meta-keywords', 'created_at', 'order', 'excerpt' ];

    // public function categories()
    // {
    //     return $this->belongsToMany('Codeman\Admin\Models\Category', 'news_categories', 'news_id', 'category_id');
    // }

    public function singleCategory()
    {
        return $this->belongsToMany('Codeman\Admin\Models\Category', 'news_categories', 'news_id', 'category_id')->first();
    }

    public function singleNewscategories()
    {
        return $this->hasMany('Codeman\Admin\Models\NewsCategory');
    }

     /**
     * Get all of the categories for the news.
     */
    public function categories()
    {
        return $this->morphToMany('Codeman\Admin\Models\Category', 'categorisable');
    }

    /**
     * Get  certain  category for the news.
     */
    public function certain_category($slug)
    {
        return $this->categories()->where('slug', $slug);
    }
}

<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
    	'title',
        'first_letter',
    	'slug',
    	'short_description',
    	'content',
    	'thumbnail',
    	'logo',
    	'status',
    	'lang',
    	'parent_lang_id',
    	'order',
    	'meta_title',
    	'meta_description',
    	'meta_keywords',
    	'meta_og_title',
    	'meta_og_image',
    	'meta_og_description',
    ];

    public function getSearchableFields()
    {
        return [
            'title',
            'status',
        ];
    }

    public function products()
    {
        return $this->hasMany('Models\Product');
    }
}

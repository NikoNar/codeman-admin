<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
     protected $fillable = [
    	'lang', 
        'parent_lang_id',
        'title',
        'author',
        'thumbnail',
        'content',
        'order', 
        'slug',
        'language_id'
    ];
    public function language()
    {
        return $this->belongsTo('Codeman\Admin\Models\Language');
    }
}

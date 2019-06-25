<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
     protected $fillable = [
    	'lang', 
        'parent_lang_id',
        'title',
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

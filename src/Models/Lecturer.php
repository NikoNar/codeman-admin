<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Lecturer extends Model
{
    protected $fillable = [
        'lang',
        'parent_lang_id',
        'title',
        'position',
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
    public function categories()
    {
        return $this->morphToMany('Codeman\Admin\Models\Category', 'categorisable');
    }
}

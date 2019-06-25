<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $fillable = [
    	'lang', 
        'parent_lang_id',
        'title',
        'position',
        'thumbnail',
        'description',
        'start_date',
        'order', 
        'duration',          
        'frequency',          
        'price',          
        'slug',
        'language_id'
    ];
    public function language()
    {
        return $this->belongsTo('Codeman\Admin\Models\Language');
    }
}

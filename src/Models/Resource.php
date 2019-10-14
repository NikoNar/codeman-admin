<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $resource_type;
    protected $fillable = [ 'parent_lang_id', 'title', 'slug', 'type', 'status', 'content', 'thumbnail', 'meta-title', 'meta-description', 'meta-keywords', 'order', 'lang','created_at' ];


    public function language()
    {
        return $this->belongsTo('Codeman\Admin\Models\Language', 'lang', 'code');
    }
    public function metas()
    {
        return $this->hasMany('Codeman\Admin\Models\Resourcemeta', 'resource_id');
    }
    public function categories()
    {
        return $this->morphToMany('Codeman\Admin\Models\Category', 'categorisable');
    }

    public function relations()
    {
        return $this->belongsToMany('Codeman\Admin\Models\Resource', 'resourceables', 'resource_id', 'resourceable_id');
    }

    public function relations_rev()
    {
        return $this->belongsToMany('Codeman\Admin\Models\Resource', 'resourceables', 'resourceable_id', 'resource_id');
    }



}

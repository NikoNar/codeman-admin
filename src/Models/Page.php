<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['parent_id', 'lang', 'parent_lang_id', 'title', 'slug', 'status', 'content', 'thumbnail', 'template', 'meta-title', 'meta-description', 'meta-keywords', 'order', 'language_id' ];
 
	public function parent()
	{
		return $this->belongsTo('Codeman\Admin\Models\Page', 'parent_id');
	}

	public function children()
	{
		return $this->hasMany('Codeman\Admin\Models\Page', 'parent_id');
	}

	public function metas()
	{
		return $this->hasMany('Codeman\Admin\Models\Pagemeta', 'page_id');
	}

    public function language()
    {
        return $this->belongsTo('Codeman\Admin\Models\Language');
    }
}

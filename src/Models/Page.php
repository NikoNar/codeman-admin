<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $fillable = ['parent_id', 'lang', 'parent_lang_id', 'title', 'slug', 'status', 'content', 'thumbnail', 'template', 'meta-title', 'meta-description', 'meta-keywords', 'order', 'is_content_builder', 'content_builder' ];
 
 	public function getSearchableFields()
 	{
 	    return [
 	        'title',
 	        'status',
 	        'created_at'
 	    ];
 	}

 	/**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'is_content_builder' => 'boolean',
        'content_builder' => 'array',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'parents_slug',
        'permalink',
        'permalink_excerpt_slug',
    ];

	public function parent()
	{
		return $this->belongsTo('Codeman\Admin\Models\Page', 'parent_id');
	}

	public function children()
	{
		return $this->hasMany('Codeman\Admin\Models\Page', 'parent_id');
	}

    public function recursiveChildren() {
        return $this->children()->with('recursiveChildren');
        //It seems this is recursive
    }

    public function recursiveParent() {
        return $this->parent()->with('recursiveParent');
        //It seems this is recursive
    }

	public function metas()
	{
		return $this->hasMany('Codeman\Admin\Models\Pagemeta', 'page_id');
	}

    public function language()
    {
        return $this->belongsTo('Codeman\Admin\Models\Language', 'lang', 'code');
    }

    public function pagetemplate()
    {
        return $this->belongsTo('Codeman\Admin\Models\Module', 'template', 'id');
    }

    public function getContentBuilderArrayAttribute()
    {
    	return isJson($this->content_builder) ? json_decode($this->content_builder) : null;
    }
    
    /**
     * @return array
     */
    public function getParentsAttribute(): array
    {
        $parents = [];
        $parent = $this->parent;

        while (!is_null($parent)) {
            array_push($parents, $parent);
            $parent = $parent->parent;
        }

        return array_reverse($parents);
    }

    /**
     * @return string
     */
    public function getParentsSlugAttribute(): string
    {
        $slug = '';
        $count = 0;
        $parents = $this->getParentsAttribute();

        foreach ($parents as $parent) {
            $count++;
            $slug .= $parent->slug;

            if ($count !== count($parents)) {
                $slug .= "/";
            }
        }

        return $slug;
    }

    public function getPermalinkExcerptSlugAttribute()
    {
        $locale = app()->getLocale();

        $permalink = [];
        $permalink[] = $locale != $this->lang ? $this->lang : '';
        
        $permalink[] = $this->parentsSlug;
        return url(implode('/', array_filter($permalink)));
    }

    public function getPermalinkAttribute()
    {
        $locale = app()->getLocale();

        $permalink = [];
        $permalink[] = $locale != $this->lang ? $this->lang : null;
        
        $permalink[] = $this->parentsSlug ? $this->parentsSlug : null;
        $permalink[] = $this->slug;
        
        return url(implode('/', array_filter($permalink)));
    }
}

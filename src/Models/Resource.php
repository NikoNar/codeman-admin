<?php

namespace Codeman\Admin\Models;

use Illuminate\Database\Eloquent\Model;

class Resource extends Model
{
    protected $resource_type;
    protected $fillable = [ 
        'parent_lang_id', 
        'title', 
        'slug', 
        'type', 
        'status', 
        'content', 
        'thumbnail',        
        'order', 
        'lang','created_at', 
        'is_content_builder', 
        'content_builder',
        //SEO  
        'meta-title', 
        'meta-description', 
        'meta-keywords', 
        'og-image',
        'og-title',
        'og-description',

    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'permalink',
        'permalink_excerpt_slug',
    ];

    public function getSearchableFields()
    {
        return [
            'title',
            'status',
            'created_at'
        ];
    }

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

    public function getContentBuilderArrayAttribute()
    {
        return isJson($this->content_builder) ? json_decode($this->content_builder) : null;
    }

    public function getPermalinkExcerptSlugAttribute()
    {
        $locale = app()->getLocale();

        $permalink = [];
        $permalink[] = $locale != $this->lang ? $this->lang : '';
        
        $permalink[] = $this->type;
        return url(implode('/', array_filter($permalink)));
    }

    public function getPermalinkAttribute()
    {
        $locale = app()->getLocale();

        $permalink = [];
        $permalink[] = $locale != $this->lang ? $this->lang : null;
        
        $permalink[] = $this->type;
        $permalink[] = $this->slug;
        
        return url(implode('/', array_filter($permalink)));
    }

    public static function boot()
    {
        parent::boot();

        self::creating(function($model){
            if( '' != $slug = getUniqueSlug((new self), $model['slug'])){
                $model['slug'] = $slug;
            } elseif('' != $slug = getUniqueSlug((new self), $model['title'])) {
                $model['slug'] = $slug;
            }else{
                $model['slug'] = getUniqueSlug((new self), 'resource');
            }
            $model['meta-title'] =  isset($model['meta-title']) && !empty($model['meta-title']) ? $model['meta-title'] : $model['title'];
            $model['meta-description'] =  
                isset($model['meta-description'])  && !empty($model['meta-description']) 
                    ? $model['meta-description'] 
                    : (isset($model['content']) ? seo_description($model['content']) : null);
        });

        self::created(function($model){
            if(request()->has('category_id')){
                $model->categories()->sync(request()->get('category_id'));
            }
        });

        self::updating(function($model){
            // dd('updating boot');
        });

        self::updated(function($model){
            // dd('updated boot');
        });

        self::deleting(function($model){
            // dd('deleting boot');
        });

        self::deleted(function($model){
            // dd('deleted boot');
        });
    }
}

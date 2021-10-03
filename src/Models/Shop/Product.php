<?php

namespace Codeman\Admin\Models\Shop;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'api_id',
    	'title',
    	'slug',
    	'price',
    	'sale_price',
        'sale_percent',
    	'sku',
    	'short_description',
    	'content',
    	'thumbnail',
        // 'notes',
    	// 'images',
    	// 'category_id',
        'promortion_type',
    	'status',
    	'type',
    	'allow_order',
    	'stock_count',
    	'stock_status',
    	'brand_id',
        'sex',
    	'weight',
    	'width',
    	'height',
    	'length',
    	'lang',
    	'parent_lang_id',
    	'order',
    	'meta_title',
    	'meta_description',
    	'meta_keywords',
    	'meta_og_title',
    	'meta_og_image',
    	'meta_og_description',
        'country', // Use productmetas table instad of this field 
    ];
    // ALTER TABLE `products` ADD `api_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
    // ALTER TABLE `products` ADD `country` VARCHAR(255) NULL AFTER `sex`;

    public function getSearchableFields()
    {
        return [
            'api_id',
            'title',
            'sku',
            'content',
            'status',
            'promortion_type',
            'brand.title',
            'categories.title',
            'sex',
            'created_at',
        ];
    }

    public function getDataFilter()
    {
        return [
            [
                'name'  => 'id',
                'label' => 'ID',
                'type'  => 'text',
                'is_relation' => false, 
            ],
            [
                'name'  => 'api_id',
                'label' => 'API ID',
                'type'  => 'text',
                'is_relation' => false, 
            ],
            [
                'name'  => 'title',
                'label' => 'Title',
                'type'  => 'text',
                'is_relation' => false, 
            ],
            [
                'name'  => 'slug',
                'label' => 'Slug',
                'type'  => 'text',
                'is_relation' => false, 
            ],
            [
                'name'  => 'sku',
                'label' => 'SKU',
                'type'  => 'text',
                'is_relation' => false, 
            ],
            [
                'name'  => 'content',
                'label' => 'Content',
                'type'  => 'text',
                'is_relation' => false, 
            ],
            [
                'name'  => 'thumbnail',
                'label' => 'Thumbnail',
                'type'  => 'text',
                'is_relation' => false, 
            ],
            [
                'name'  => 'status',
                'label' => 'Status',
                'type'  => 'dropdown',
                'options' => [
                    'published' => 'Published',
                    'draft' => 'Draft',
                    'pending' => 'Pending',
                    'archive' => 'Archive',
                    'deleted' => 'Deleted',
                    'schedule' => 'Schedule',
                ],
                'is_relation' => false, 
            ],
            [
                'name'  => 'type',
                'label' => 'Type',
                'type'  => 'dropdown',
                'options' => [
                    'simple' => 'Simple',
                    'variation' => 'Variation',
                    // 'group' => 'Group',
                    // 'downloadble' => 'Downloadble',
                ],
                'is_relation' => false, 
            ],
            [
                'name'  => 'lang',
                'label' => 'Language',
                'type'  => 'language',
                'is_relation' => false, 
            ],
            [
                'name'  => 'created_at',
                'label' => 'Created Date',
                'type'  => 'datetime_picker_range',
                'is_relation' => false, 
            ],
            [
                'name'  => 'created_at',
                'label' => 'Updated Date',
                'type'  => 'datetime_picker_range',
                'is_relation' => false, 
            ],
            // [
            //     'name'  => '',
            //     'label' => '',
            //     'type'  => '',
            //     'options' => [

            //     ],
            //     'is_relation' => false, 
            //     'relation_name' => ''
            // ]

        ];
    }

    public function metas()
    {
        return $this->hasMany('\Codeman\Admin\Models\Shop\ProductMeta', 'product_id');
    }

    public function brand()
    {
        return $this->belongsTo('\Codeman\Admin\Models\Shop\Brand');
    }

    public function options()
    {
        return $this->morphToMany('\Codeman\Admin\Models\Shop\ProductOption', 'product_option_groups');
    }

    public function group_options()
    {
        return $this->hasMany('\Codeman\Admin\Models\Shop\ProductGroupOption');
    }

    public function variation_options()
    {
        return $this->hasMany('\Codeman\Admin\Models\Shop\ProductVariationOption');
    }

    public function variations()
    {
        return $this->hasMany('\Codeman\Admin\Models\Shop\Variation');
    }

    // Product Images Gallery Relation
    public function gallery()
    {
        return $this->hasMany('\Codeman\Admin\Models\Shop\ProductImages')->orderBy('sort', 'ASC');
    }

    public function language()
    {
        return $this->belongsTo('Codeman\Admin\Models\Language', 'lang', 'code');
    }
   
    public function categories()
    {
        return $this->morphToMany('Codeman\Admin\Models\Category', 'categorisable');
    }

    /**
     * Get all of the prodyct's Inventories.
     */
    public function inventories()
    {
        return $this->morphMany(\App\Models\Inventory::class, 'inventoriable');
    }

    // This is a recommended way to declare event handlers
    public static function boot() {
        parent::boot();

        static::deleting( function($resource) { // before delete() method call this
            $resource->metas()->delete();
            $resource->categories()->detach();
            $resource->gallery()->delete();
            $resource->group_options()->delete();
            $resource->variation_options()->delete();
            $resource->variations()->delete();
            // do the rest of the cleanup...
        });
    }
}

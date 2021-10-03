<?php

namespace Codeman\Admin\Models\Shop;

use App\Models\Label;
use App\Relations\HasLabelRelations;
use Illuminate\Database\Eloquent\Model;
use DB;

class Variation extends Model
{
	use HasLabelRelations; // Trait

	protected $fillable = [
		'title',
		'api_id',
		'api_product_id',
		'product_id',
		'sku',
		'sale_percent',
		'price',
		'sale_price',
		'thumbnail',
		'stock_count',
		'stock_status',
		'order',
		'status',
		'is_private',
		'secondary_thumbnail',
		'video_url',
		'seo_title',
		'label_ids'
	];

	/**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'label_ids' => 'array',
    ];

    /**
     * @var string[]
     */
    protected $appends = [
        'permalink',
        'product_name'
    ];

	// ALTER TABLE `variations` ADD `api_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
	// ALTER TABLE `variations` ADD `api_product_id` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;
	// ALTER TABLE `variations` ADD `title` VARCHAR(255) NULL DEFAULT NULL AFTER `id`;

	public function getSearchableFields()
	{
	    return [
	        'title',
	        'sku',
	        'status',
	        'price',
	        'sale_price',
	        'created_at',
	        'updated_at',
	    ];
	}

	public function labels()
    {
        return $this->belongsToLabel(\App\Models\Label::class, "id", "label_ids")
            ->where('status', 'active'); // Relationship
    }

	public function wishlist()
	{
		$relation = $this->hasOne('Codeman\Admin\Models\Shop\Cart')
		->where('cart_type', 'wishlist');
		if(auth()->check()){
			$relation = $relation->where('user_id', auth()->id());
		}else{
			$relation = $relation->where('session_id', get_user_session_id());
		}
		return $relation;

	}

	public function views()
	{
	    return $this->hasMany('Codeman\Admin\Models\Shop\Tracker','resource_id', 'id')
	    ->where('resource', 'product');
	}

  	/**
 	 * Get the parent inventoriable model (products or variations).
 	 */
	public function inventoriable()
	{
  		return $this->morphTo('App\Models\Warehouse');
	}

	/**
     * Get all sibling variation.
     */
	public function siblings()
	{
		return $this->hasMany('Codeman\Admin\Models\Shop\Variation');
	}

	/**
     * Get all product options that are assigned this variation.
     */
	public function variation_options()
	{
	    return $this->hasMany('\Codeman\Admin\Models\Shop\ProductVariationOption')
	    ->groupBy('product_option_id');
	    // ->withPivot(['product_option_id', 'product_option_group_id']);
	}

	/**
     * Get all product options with groups that are assigned this variation.
     */
	public function options()
	{
		return $this->belongsToMany(
			\Codeman\Admin\Models\Shop\ProductOption::class,
			'product_variation_options'
		)
	    ->withPivot(['product_option_id', 'product_option_group_id']);
	}

    /**
     * Get variation season color.
     */
	public function seasonColors()
	{
		return $this->belongsToMany(
			\App\Models\SeasonColor::class,
			'season_color_variations'
		)
	    ->withPivot(['season_color_id', 'color_group_id']);
	}

	/**
     * Get product that is assigned this variation.
     */
	public function product()
	{
		return $this->belongsTo('\Codeman\Admin\Models\Shop\Product');
	}

	/**
     * Get all of the gallery images that are assigned this variation.
     */
	public function gallery()
	{
		return $this->morphToMany(\Codeman\Admin\Models\Image::class, 'imageable')
		->where('type', 'gallery')->withPivot(['alt', 'sort', 'size']);
	}

	/**
	 * Get all of the prodyct's Inventories.
	 */
	public function inventories()
	{
	    return $this->morphMany(\App\Models\Inventory::class, 'inventoriable');
	}

    /**
     * Get all Looks where varaition assigned.
     */
	public function getVariationLooks($id)
	{
		$lang = \App::getLocale();

		return \App\Models\Look::select('id', 'title', 'slug', 'thumbnail')
		->has('lookbookMorph')
		->with('lookbookMorph', function($q){
			$q->with('lookbook');
		})
		->join('look_products', 'look_products.look_id', 'looks.id')
		->where('looks.status', 'published')
		->where('lang', $lang)
		->where('look_products.variation_id', $id)
		->get();
	}

    /**
     * Get the provided variation related variations ids by finding them on all looks where current variation attached.
     * $variation_id not included on returned list of ID's
     * @param variation_id (int)
     * @return array
     */
    public function getVariationRelatedLooksVariationIds($variation_id)
    {
        // SELECT *
        //  FROM look_products lp
        //   INNER JOIN look_products lp_1
        //    ON lp.look_id = lp_1.look_id
        //    AND lp_1.variation_id = 1809

        return \DB::table('look_products as lp')
            ->select('lp.variation_id')
            ->join('look_products as lp_1', 'lp.look_id', 'lp_1.look_id')
            ->where('lp_1.variation_id', $variation_id)
            ->where('lp.variation_id', '!=', $variation_id)
            ->get()
            ->unique()
            ->pluck('variation_id')
            ->toArray();
    }

    public function prepareVariationsCollection($category_slug = null, $properties = null, $variation_ids = null, $order = 'order')
    {
        $additional_filters = [];

        $variations = self::
        select(
            'variations.id',
            'variations.product_id',
            'variations.label_ids',
            'variations.title',
            'variations.seo_title',
            'variations.price',
            'variations.sale_price',
            'variations.thumbnail',
            'variations.secondary_thumbnail',
            'variations.order',
            'variations.status',
            'variations.created_at',
            'variations.updated_at',
            'cm_categories.title as category_title',
            'cm_categories.slug as category_slug',
        )
            ->join('categorisables', 'categorisables.categorisable_id', 'variations.product_id')
            ->join('cm_categories', 'cm_categories.id', 'categorisables.category_id')
            ->where('cm_categories.type', 'products')
            ->join('product_variation_options',
                'product_variation_options.variation_id', '=', 'variations.id'
            )->whereHas('product', function($q){
                $q->where('status', 'published');
            })
            ->with([
                // 'seasonColors',
                'labels',
                'wishlist' => function($q){
                    //check if exists user session cookie
                    $session_id = get_user_session_id();
                    if(auth()->check()){
                        return $q->select('id', 'user_id', 'session_id', 'variation_id')->where('user_id', auth()->id());
                    }elseif($session_id){
                        return $q->select('id', 'user_id', 'session_id', 'variation_id')->where('session_id', $session_id);
                    }
                },
                'options' => function($q){
                    return $q->where('product_options.product_option_group_id', 2); // 2=color
                },
                'gallery',
                'product' => function($q){
                    $q->select('id', 'title', 'thumbnail', 'slug')
                        ->where('status', 'published')
                        ->with([
                            'metas',
                            // 'variations' => function($q){
                            //     return  $q->with('options');
                            // }
                        ]);
                },
                'inventories'
            ]);

        if(is_array($variation_ids)){
            $variations = $variations->whereIn('variations.id', $variation_ids);
        }

        if($category_slug){
            $variations = $variations->where('cm_categories.slug', $category_slug);
            $additional_filters['category'] = $category_slug;
        }

        if($properties && !empty($properties)){
            $filtered_variation_ids = $this->filterVariationsByProperties($properties, array(), $additional_filters);
            $variations = $variations->whereIn('variations.id', $filtered_variation_ids);
        }

        $variations = $variations
            ->where('product_variation_options.product_option_group_id', 2)
            ->where('variations.status', 'published');

        switch ($order) {
            case 'price_asc':
                $variations = $variations->orderBy('variations.price', 'DESC');
                break;
            case 'price_desc':
                $variations = $variations->orderBy('variations.price', 'ASC');
                break;
            case 'new':
                $variations = $variations->orderByRaw("case when label_ids like '%3%' then label_ids end desc"); // %3% id label "new" id
                break;
            case 'bestseller':
                $variations = $variations->orderByRaw("case when label_ids like '%1%' then label_ids end desc"); // %3% id label "new" id
                break;
            case 'sale':
                $variations = $variations->orderByRaw("case when label_ids like '%2%' then label_ids end desc"); // %3% id label "new" id
                break;
            case 'group_order':
                if(is_array($variation_ids)){
                    $rawOrder = \DB::raw(sprintf('FIELD(variations.id, %s)', implode(',', $variation_ids)));
                    $variations = $variations->orderByRaw($rawOrder);
                }
                break;
            default:
                $variations = $variations->orderBy('variations.order', 'DESC');
                break;
        }

        $variations = $variations->groupBy([
            'product_variation_options.product_option_id',
            'product_variation_options.product_id'
        ]);

        return $variations;
    }

	public function getVariationsColorsAndSizesWithStock($variation_ids)
	{
		$variations_colors_grouped = self::distinct()->select(
		    'variations.id as main_var_id',
		    'var.id as variation_id',
		    'var.product_id',
		    'var.thumbnail',
		    'var.secondary_thumbnail',
		    'var.price',
		    'var.sale_price',
		    // 'var.title',
		    'products.title',
		    'product_variation_options.product_option_group_id',
		    'product_variation_options.product_option_id',
		    'product_options.name',
		    'product_options.value',
		    'product_options.order',
		    'season_colors.color_name',
		    'season_colors.color_option_id',
		    'season_colors.image',
		    'inventories.quantity'
		    // \DB::raw('SUM(inventories.quantity) as total_qty')
		)
		->join('products', 'products.id', 'variations.product_id')
		->join('variations as var', 'variations.product_id', 'var.product_id')
		->join('product_variation_options', 'product_variation_options.variation_id', 'var.id')
		->join('product_options', 'product_options.id', 'product_variation_options.product_option_id')
		->join('season_color_variations', 'season_color_variations.variation_id', 'var.id')
		->join('season_colors', 'season_colors.id', 'season_color_variations.season_color_id')
		->join('inventories', 'inventories.inventoriable_id', 'var.id')
		->whereIn('variations.id', $variation_ids)
		->whereIn('product_variation_options.product_option_group_id', [2,3])
		->get()
		->groupBy(['main_var_id', 'color_option_id'])
		->toArray();

		$stock_total_count = [];

		foreach($variations_colors_grouped as $var_id => $colors)
		{
		    foreach($colors as $color_id => $options)
		    {
		        $color_total = 0;
		        $sizes_stock = [];

		        foreach($options as $key => $option)
		        {
		            if($option['product_option_group_id'] == 2){
		                unset($variations_colors_grouped[$var_id][$color_id][$key]);
		                continue;
		            }
		            $color_total = $color_total + $option['quantity'];
		            $sizes_stock[$option['product_option_id']] = [
		                "variation_id" => $option['variation_id'],
		                "sum_quantity" => $option['quantity'],
		                "option_id" => $option['product_option_id'],
		                "option_group_id" => $option['product_option_group_id'],
		                "option_name" => $option['name'],
		                "option_value" => $option['value'],
		                "option_order" => $option['order'],
		            ];
		            $stock_total_count[$var_id][$color_id] = [
		            	'product_id' => $option['product_id'],
		                'color_id' => $color_id,
		                'price' => $option['price'],
		                'sale_price' => $option['sale_price'],
		                'thumbnail' => $option['thumbnail'],
		                'secondary_thumbnail' => $option['secondary_thumbnail'],
		                // 'option_group_id' => $option['product_option_group_id'],
		                'option_group_id' => 2,
		                'color_name' => $option['color_name'],
		                'color_value' => $option['image'],
		                'total_stock' => $color_total,
		                'sizes_stock' => $sizes_stock
		            ];
		        }
		    }
		}
		return $stock_total_count;
	}

	public function getVariationsGroupedByColor($category_slug = null, $properties = null, $product_id = null)
	{

		// SELECT variations.id, CONCAT(products.title, ' (',product_options.name,')') as new_name, product_options.name as option_name FROM variations
		// JOIN products ON variations.product_id = products.id
		// JOIN product_variation_options ON product_variation_options.variation_id = variations.id
		// JOIN product_options ON product_variation_options.product_option_id = product_options.id
		// WHERE product_variation_options.product_option_group_id = 2
		// AND products.status = 'published'
		// AND products.lang = 'ru'
		// AND product_options.status = 'published'
		// AND variations.status = 'published'
		// GROUP BY new_name
		// ORDER BY variations.order DESC

	    $additional_filters = [];

	    $variations = self::
	    select(
	        'variations.id',
	        'variations.product_id',
	        \DB::raw("CONCAT(products.title,' (',product_options.name, ')') as title"),
	        'product_options.name as option_name',
	    )
	    ->join('products', 'products.id', 'variations.product_id')
	    ->join('product_variation_options', 'product_variation_options.variation_id', '=', 'variations.id')
	    ->join('product_options', 'product_options.id', 'product_variation_options.product_option_id')
	    ->where('products.status', 'published')
	    ->where('variations.status', 'published')
	    ->where('product_options.status', 'published')
	    ->where('products.lang', 'ru');


    	if($product_id && is_array($product_id)){
    	    $variations = $variations->whereIn('products.id', $product_id);
    	}elseif($product_id && is_numeric($product_id)){
    	    $variations = $variations->where('products.id', $product_id);
    	}

	    if($product_id && is_array($product_id)){
	    	return $variations->where('product_variation_options.product_option_group_id', 2)
		    ->orderBy('variations.title', 'ASC')
		    ->groupBy(['title']);
	    }else{
		    $variations = $variations->where('product_variation_options.product_option_group_id', 2)
		    ->orderBy('variations.title', 'ASC')
		    // ->orderBy('variations.order', 'DESC')
		    ->groupBy(['title']);
	    }
	    return $variations;
	}

	public function getVariationsOptionsGrouped($product_ids, $group_ids = null, $groupBy = null)
	{
	    $variations_options_grouped = ProductGroupOption::
	    distinct()
	    ->select(
	        'product_options.name as option_name',
	        'product_options.value as option_value',
	        'product_option_groups.name as group_name',
	        'product_group_options.product_id',
	        'product_group_options.product_option_id',
	        'product_group_options.product_option_group_id',
	        // 'season_colors.color_name',
	        // 'season_colors.image',
	    )
	    ->join('product_option_groups', 'product_option_groups.id','=','product_group_options.product_option_group_id')
	    ->join('product_options', 'product_options.id', '=', 'product_group_options.product_option_id')
	    ->join('product_variation_options', 'product_options.id', '=', 'product_variation_options.product_option_id')
	    ->join('variations', 'variations.product_id', '=', 'product_group_options.product_id')
	    // ->join('season_color_variations', 'season_color_variations.variation_id', 'variations.id')
	    // ->join('season_colors', 'season_colors.id', 'season_color_variations.season_color_id')
	    ->where('variations.status', 'published');

	    if($product_ids && is_array($product_ids)){
	        $variations_options_grouped = $variations_options_grouped->whereIn('product_group_options.product_id', $product_ids);
	    }elseif($product_ids && is_numeric($product_ids)){
	        $variations_options_grouped = $variations_options_grouped->where('product_group_options.product_id', $product_ids);
	    }

	    if($group_ids && is_array($group_ids)){
	        $variations_options_grouped = $variations_options_grouped->whereIn('product_option_groups.id', $group_ids);
	    }elseif($group_ids && !is_numeric($group_ids)){
	        $variations_options_grouped = $variations_options_grouped->where('product_option_groups.id', $group_ids);
	    }

	    $variations_options_grouped = $variations_options_grouped->where('product_option_groups.status', 'published')
	    ->orderBy('product_options.name', 'ASC', SORT_REGULAR)
	    ->get();

	    if($groupBy){
	    	$variations_options_grouped = $variations_options_grouped->groupBy($groupBy);
	    }

	    $variations_options_grouped = $variations_options_grouped->toArray();

	    if($groupBy == 'product_id'){
		    $products_options_groups = [];
		    $color_option_ids = [];
		    if(!empty($variations_options_grouped)){
			    foreach($variations_options_grouped as $product_id  => $options){
			    	foreach($options as $key  => $option){
			    		$products_options_groups[$product_id][$option['product_option_group_id']][] = $option;
			    		if($option['product_option_group_id'] == 2){
			    			$color_option_ids[] = $option['product_option_id'];
			    		}
			    	}
			    }
		    }
		    //Finding Season colors of prducts list
		    $season_colors = \App\Models\SeasonColor::select('season_colors.color_name', 'season_colors.image', 'season_colors.color_option_id', 'variations.product_id')
		    ->join('season_color_variations', 'season_color_variations.season_color_id', 'season_colors.id')
		    ->join('variations', 'season_color_variations.variation_id', 'variations.id')
		    ->whereIn('variations.product_id', $product_ids)
		    ->whereIn('season_colors.color_option_id', $color_option_ids)
		    ->groupBy('color_option_id', 'product_id')
		    ->get()
		    ->toArray();

		    foreach($products_options_groups as $product_id => $options)
		    {
		    	if(isset($options[2])){
		    		foreach($options[2] as $item_key => $item){
		    			foreach($season_colors as $color){
		    				if(
		    					$color['product_id'] == $item['product_id'] &&
		    				 	$color['color_option_id'] == $item['product_option_id']
		    				 ){
		    					$products_options_groups[$product_id][2][$item_key]['option_name'] = $color['color_name'];
		    					$products_options_groups[$product_id][2][$item_key]['option_value'] = $color['image'];
		    				}
		    			}
		    		}
		    	}
		    }
		    return $products_options_groups;
	    }

        $color_option_ids = [];
        if(!empty($variations_options_grouped)){
    	    foreach($variations_options_grouped as $item_key  => $options){
    	    	foreach($options as $key  => $option){
    	    		if($option['product_option_group_id'] == 2){
    	    			$color_option_ids[] = $option['product_option_id'];
    	    		}
    	    	}
    	    }
        }

        //Finding Season colors of prducts list
        $season_colors = \App\Models\SeasonColor::select('season_colors.color_name', 'season_colors.image', 'season_colors.color_option_id', 'variations.product_id')
        ->join('season_color_variations', 'season_color_variations.season_color_id', 'season_colors.id')
        ->join('variations', 'season_color_variations.variation_id', 'variations.id');

        if($product_ids && is_array($product_ids)){
            $season_colors = $season_colors->whereIn('variations.product_id', $product_ids);
        }elseif($product_ids && is_numeric($product_ids)){
            $season_colors = $season_colors->where('variations.product_id', $product_ids);
        }
        $season_colors = $season_colors->whereIn('season_colors.color_option_id', $color_option_ids)
        ->groupBy('color_option_id', 'product_id')
        ->get()
        ->toArray();

        if(isset($variations_options_grouped[2])){
    		foreach($variations_options_grouped[2] as $item_key => $item){
    			foreach($season_colors as $color){
    				if(
    					$color['product_id'] == $item['product_id'] &&
    				 	$color['color_option_id'] == $item['product_option_id']
    				){
    					$variations_options_grouped[2][$item_key]['option_name'] = $color['color_name'];
    					$variations_options_grouped[2][$item_key]['option_value'] = $color['image'];
    				}
    			}
    		}
        }
	    return $variations_options_grouped;
	}

    /**
     * Get array list ID's of variation sized of variation color from provided variation ID.
     * @param $variation_id int
     * @param $option_group_id int // Default 2 = color, 3 = size, etc.. check DB product_option_groups table for all
     * @return array
     */
    public function getVariationSiblingsIdsFilteredByOptionGroupID($variation_id, $option_group_id = 2){
        $resultsArray = [];

        if(is_array($variation_id) && !empty($variation_id)){
            $variation_id = implode(',', $variation_id);
            $results = DB::select( "SELECT b.variation_id from product_variation_options as a
                INNER JOIN product_variation_options as b
                ON b.product_id = a.product_id and b.product_option_id = a.product_option_id
                WHERE a.variation_id IN (${variation_id}) and a.product_option_group_id = ${option_group_id}" );
        }else if(!is_array($variation_id)){
            $results = DB::select( "SELECT b.variation_id from product_variation_options as a
                INNER JOIN product_variation_options as b
                ON b.product_id = a.product_id and b.product_option_id = a.product_option_id
                WHERE a.variation_id = ${variation_id} and a.product_option_group_id = ${option_group_id}" );
        }else{
            return [];
        }

        if($results){
            $resultsArray = array_map(function ($value) {
                return $value->variation_id;
            }, $results);
        }
        return $resultsArray;
    }

    public function getVariationIdsFilteredByOptionIdAndProductId($product_id, $option_id){
        $resultsArray = [];

        $results = DB::select( "select variation_id from product_variation_options where product_option_id = ${option_id} and product_id = ${product_id}" );

        if($results){
            $resultsArray = array_map(function ($value) {
                return $value->variation_id;
            }, $results);
        }
        return $resultsArray;
    }
    /**
     * Get list of ID's of provided variation related sizes.
     * @param variation (obj)
     * @return array
     */
	public function getVariationRelatedSizesIds($variation)
	{
		return ProductVariationOption::select('variation_id')
		    ->where('product_id', $variation->product->id)
		    ->where('product_option_group_id', 2) //color
		    ->where('product_option_id', $variation->options[0]->pivot->product_option_id)
		    ->get()
		    ->pluck('variation_id')
		    ->toArray();
	}

    /**
     * Get stock qty for all size of provided variation.
     * @param variation (obj)
     * @return array
     */
	public function getVariationSizesStock($variation)
	{
		$sizes_variations_ids = self::getVariationRelatedSizesIds($variation);

		$sizes_variations = self::select('id', 'title')
		->with('options', function($q){
		    $q->where('product_options.product_option_group_id', 3);
		    // ->join('product_options', 'variation_options.product_option_id', 'product_options.id');
		})
		->withSum('inventories', 'quantity')
		->find($sizes_variations_ids)
		->toArray();

		$sizes_stock = [];
		foreach ($sizes_variations as $key => $item) {
		    if(isset($item['options'][0])){
		        $size = $item['options'];
		        $size_option_id = $item['options'][0]['id'];

		        $sizes_stock[$size_option_id]['variation_id'] = $item['id'];
		        $sizes_stock[$size_option_id]['sum_quantity'] = $item['inventories_sum_quantity'];
		        $sizes_stock[$size_option_id]['option_id'] = $size_option_id;
		        $sizes_stock[$size_option_id]['option_group_id'] = $item['options'][0]['product_option_group_id'];
		        $sizes_stock[$size_option_id]['option_name'] = $item['options'][0]['name'];
		        $sizes_stock[$size_option_id]['option_value'] = $item['options'][0]['value'];
		        $sizes_stock[$size_option_id]['option_order'] = $item['options'][0]['order'];
		        // $sizes_stock[$size_option_id]['product_id'] = $item['options'][0]['product_id'];
		    }
		}
		return $sizes_stock;
	}

    /**
     * Calculate variation color total stock qty.
     * @param sizes_stock (array)
     * @return array
     */
	public function getVariationColorTotalStock($sizes_stock)
	{
		$stock_total_count = 0;
		array_walk_recursive($sizes_stock, function($value, $key) use (&$stock_total_count){
		    $stock_total_count = $key == 'sum_quantity' ? $stock_total_count + intval($value) : $stock_total_count;
		});
		return $stock_total_count;
	}

    /**
     * Get Product variations options groupd by color with total stock qty for each color
     * and all sizes with stock qty for each size.
     * @param products_id (int)
     * @return array
     */
	public function getRelatedVariationsOfColors($product_id)
	{
	    $variations_ids = self::getVariationsGroupedByColor(null, null, $product_id)->get()->pluck('id')->toArray();

	    $variations = self::prepareVariationsCollection(null, null, $variations_ids)->get();

	    $stock_total_count = [];

	    foreach ($variations as $key => $variation){
	        $variation_color_option = $variation->options->where('product_option_group_id')->first();
	        $variation_color = $variation->seasonColors->first();

	        // get variation stock qty for each size.
	        $sizes_stock = self::getVariationSizesStock($variation);

	        //calculate total stock for current color item
	        if($variation_color){
	            // $color_id = $variation_color->pivot->product_option_id; // old login
	            $color_id = $variation_color->color_option_id; //new login
	            $stock_total_count[$color_id]['color_id'] = $color_id;
	            $stock_total_count[$color_id]['option_group_id'] = 2;
	            // $stock_total_count[$color_id]['color_name'] = $variation_color->name; // old login
	            $stock_total_count[$color_id]['color_name'] = $variation_color->color_name; //new login
	            // $stock_total_count[$color_id]['color_value'] = $variation_color->value; // old login
	            $stock_total_count[$color_id]['color_value'] = $variation_color->image; //new login
	            // $stock_total_count[$color_id]['color_order'] = $variation_color->order;

	            $stock_total_count[$color_id]['total_stock'] = self::getVariationColorTotalStock($sizes_stock);
	            $stock_total_count[$color_id]['sizes_stock'] = $sizes_stock;
	        }
	    }

	    // //Finding Season colors of prducts list
	    // $season_colors = \App\Models\SeasonColor::select('season_colors.color_name', 'season_colors.image', 'season_colors.color_option_id', 'variations.product_id')
	    // ->join('season_color_variations', 'season_color_variations.season_color_id', 'season_colors.id')
	    // ->join('variations', 'season_color_variations.variation_id', 'variations.id');

	    // if($product_ids && is_array($product_ids)){
	    //     $season_colors = $season_colors->whereIn('variations.product_id', $product_ids);
	    // }elseif($product_ids && is_numeric($product_ids)){
	    //     $season_colors = $season_colors->where('variations.product_id', $product_ids);
	    // }
	    // $season_colors = $season_colors->whereIn('season_colors.color_option_id', $color_option_ids)
	    // ->groupBy('color_option_id', 'product_id')
	    // ->get()
	    // ->toArray();

	    // if(isset($variations_options_grouped[2])){
	    //     foreach($variations_options_grouped[2] as $item_key => $item){

	    //         foreach($season_colors as $color){
	    //             if(
	    //                 $color['product_id'] == $item['product_id'] &&
	    //                 $color['color_option_id'] == $item['product_option_id']
	    //              ){
	    //                 $variations_options_grouped[2][$item_key]['option_name'] = $color['color_name'];
	    //                 $variations_options_grouped[2][$item_key]['option_value'] = $color['image'];
	    //             }
	    //         }
	    //     }
	    // }


	    return $stock_total_count;
	}

    /**
     * Get Permalink Attribute (Getter)
     * @return variation object
     */
	public function getPermalinkAttribute()
	{

	    return $this->title;
//	    $locale = app()->getLocale();
//	    $prefix = 'catalog';
//	    $permalink = [];
//	    $permalink[] = $locale != $this->lang ? $this->lang : '';
//	    $permalink[] = $prefix;
//	    $permalink[] = isset($this->product) && isset($this->product->categories[0]) ? $this->product->categories[0]->slug : 'unknown';
//	    $permalink[] = isset($this->product) ? $this->product->slug : null;
//	    $permalink[] = $this->options->where('product_option_group_id', 2)->first() ? $this->options->where('product_option_group_id', 2)->first()->id : null;
//
//	    // $permalink[] = $this;
//	    return url(implode('/', array_filter($permalink)));

	}

    /**
     * Get Product Name Attribute (Getter)
     * @return variation object
     */
	public function getProductNameAttribute()
    {
        return $this->title;
//        if($this->product && $this->options->where('product_option_group_id', 2)->first()){
//            return $this->product->title.' ('.$this->options->where('product_option_group_id', 2)->first()->name.')';
//        }elseif ($this->product()){
//            return $this->product();
//        }else{
//            return $this->title;
//        }

    }

    private function filterVariationsByProperties($properties, $variation_ids = [],  $additional_filters = [])
    {
        foreach ($properties as $group_id => $options_arr) {

            if($group_id == 'color'){
                $options_arr = \App\Models\SeasonColor::select('color_option_id')
                    ->whereIn('color_group_id', $options_arr)
                    ->groupBy('color_option_id')
                    ->orderBy('color_option_id', 'DESC')
                    ->pluck('color_option_id')->toArray();
            }
            $variations = self::
            select(
                'variations.id',
                'variations.product_id',
            )
                ->join('product_variation_options',
                    'product_variation_options.variation_id', '=', 'variations.id'
                );

            if(isset($additional_filters['category'])){
                $variations = $variations
                    ->join('categorisables', 'categorisables.categorisable_id', 'variations.product_id')
                    ->join('cm_categories', 'cm_categories.id', 'categorisables.category_id')
                    ->where('cm_categories.type', 'products')
                    ->where('cm_categories.slug', $additional_filters['category']);
            }

            if(!empty($variation_ids)){
                $variations = $variations->whereIn('variations.id', $variation_ids);
            }

            $variations = $variations
                ->whereIn('product_variation_options.product_option_id', $options_arr);
            $variations = $variations->get()->pluck('id')->unique()->toArray();

            $variation_ids = $variations;
            unset($properties[$group_id]);
            if(!empty($properties)){
                $this->filterVariationsByProperties($properties, $variation_ids, $additional_filters);
            }
        }
        return $variation_ids;
    }

    /**
     * Assign or Remove label "SALE" to variation
     * Function check if resource has sale_price than attaching label,
     * otehrwise removing it.
     * @param resource (Variation) object
     * @return it's didn't return any value.
     */
    private static function assignOrRemoveSaleLable($resource){
        $sale_lable = Label::where('type', 'sale')->first();

        if($sale_lable){
            if(floatval($resource->sale_price) > 0){
                $attached_lables_array = (array) $resource->label_ids;
                if(!in_array($sale_lable->id, $attached_lables_array)){
                    array_push($attached_lables_array, $sale_lable->id);
                }
            }else{
                $attached_lables_array = (array) $resource->label_ids;
                if(false !== $slabel_index = array_search($sale_lable->id, $attached_lables_array)){
                    unset($attached_lables_array[$slabel_index]);
                }
            }
        }
        $resource['label_ids'] = $attached_lables_array;
    }

    /**
     * Booting Model Changes
     */
    public static function boot() {
        parent::boot();

        static::created( function($resource) { // before create() method call this

        });

        static::updating( function($resource) { // after update() method call this
            // Assign or Remove Label "Sale" in case if variation has sale price
            self::assignOrRemoveSaleLable($resource);
        });

        static::updated( function($resource) { // before update() method call this
//            dd($resource);
        });

        static::deleted( function($resource) { // before delete() method call this

        });
    }
}

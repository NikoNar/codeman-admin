<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\SearchRequest;
use Codeman\Admin\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Cart;
use App\Models\ProductOption;
use App\Models\ProductOptionGroup;
use App\Models\ProductGroupOption;
use App\Models\Variation;
use App\Models\ProductVariationOption;

class ProductsController extends Controller
{

    public function __construct(
        Product $product, 
        Category $category,
        Brand $brand,
        ProductOptionGroup $productOptionGroup_model,
        ProductGroupOption $productGroupOption_pivot, 
        Variation $variation_model,
        ProductVariationOption $productVariationOption_pivot,
        ProductOption $productOption,
        Cart $cart
    )
    {

        $this->middleware('web');
        $this->lang = \App::getLocale();
        $this->product = $product;
        $this->brand = $brand;
        // $this->cart = $cart;

        $this->productOptionGroup_model = $productOptionGroup_model;
        $this->productGroupOption_pivot = $productGroupOption_pivot;
        $this->variation_model = $variation_model;
        $this->productVariationOption_pivot = $productVariationOption_pivot;
        $this->productOption = $productOption;

        $this->category = $category;
        $this->module = 'products';

        //check if exists user session cookie
        $this->session_id = $cart->get_cookie();

        // $this->middleware('tracker')->only(['show', 'quick_view']);
    }

    public function category_products($category_slug)
    {
        // if(request()->ajax()){
        //     return $this->ajaxGetProducts('category', $this->category, $category_slug);
        // }
        return $this->getFilters('category', $this->category, $category_slug);
    }

    public function brand_products($brand_slug)
    {
        return $this->getFilters('brands', $this->brand, $brand_slug);
        // return $this->getProducts('brands', $this->brand, $brand_slug);
    }

    public function sale()
    {
        return $this->getFilters('sale', null, null);
        // return $this->getProducts('sale', null, null);
    }

    public function search(SearchRequest $request)
    {
        return $this->getFilters('title', null, $request->get('q'));
        // return $this->getProducts('title', null, $request->get('q'));
    }

    public function getFilters($show_by, $model = null, $slug = null)
    {
        if($model){
            $resource = $model->select('id', 'title', 'slug', 'thumbnail')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('lang', $this->lang)
            ->first();

            if(!$resource){
                return abort(404);
            }            
        }

        $products = $this->product        
        ->select('products.id', 'products.brand_id', 'products.sale_percent', 'products.sex')
        // ->join('brands', 'brands.id', '=', 'products.brand_id')
        // ->leftjoin('variations', 'products.id', '=', 'variations.product_id')
        ->join('categorisables', 'categorisables.categorisable_id', 'products.id')
        ->join('cm_categories', 'cm_categories.id', 'categorisables.category_id')
        ->where('cm_categories.type', 'products');

        switch ($show_by) {
            case 'brands':
                    $products = $products->where('products.brand_id', $resource->id);
                break;
            case 'category':
                if(!request()->has('cat') || empty(request()->get('cat'))){
                    $products = $products->where('cm_categories.slug', $slug);
                }
                break;
            case 'title':
                $brands_ids = \App\Models\Brand::select('id', 'title')->where($show_by, 'LIKE', '%'.request()->get('q').'%')
                ->where('status', 'published')
                ->where('lang', $this->lang)
                ->get()->pluck('id')->toArray();
                
                if(!empty($brands_ids)){
                    $products = $products->whereIn('products.brand_id', $brands_ids)
                    ->where('products.lang', $this->lang)
                    ->where('products.thumbnail', '<>', NULL)
                    ->where('products.thumbnail', '<>', 'NULL')
                    ->where('products.thumbnail', '<>', '')
                    ->orWhere('products.'.$show_by, 'LIKE', '%'.request()->get('q').'%');
                }else{
                    $products = $products->where('products.'.$show_by, 'LIKE', '%'.request()->get('q').'%');
                }
                break;
            case 'sale':
                $products = $products->where('products.sale_percent', '>', 0)->orderBy('sale_percent', 'DESC');
                break;
        }

        $products = $products->where('products.status', 'published')
        ->where('products.lang', $this->lang);
        // ->where('products.thumbnail', '<>', null);
        // ->orderBy('order', 'DESC')
        // ->groupBy('products.id');
        $products_filter = $products->get();
        $product_ids = $products_filter->pluck('id')->toArray();
        // dd($product_ids);

        // Get Categories For Filter
        $categories = $this->category->distinct()
        ->select('cm_categories.id', 'cm_categories.title', 'cm_categories.slug', 'cm_categories.level', 'cm_categories.node', 'cm_categories.order', 'cm_categories.parent_id')
        ->join('categorisables','categorisables.category_id', 'cm_categories.id')
        ->where('cm_categories.type', 'products')
        // ->where('categorisables.categorisable_type', 'App\Models\Product')
        ->where('cm_categories.lang', $this->lang)
        ->where('status', 'published')
        ->whereIn('categorisables.categorisable_id', $product_ids)
        ->orderBy('level', 'ASC')
        ->orderBy('order', 'DESC')
        ->get()->groupBy('parent_id');


        //Register Empty Filters array
        $filters = [];
        //Get Sale Percents For Filter
        $sale_percents = $products_filter->pluck('sale_percent')->toArray(); 
        $sale_percents = array_filter(array_unique($sale_percents));
        $sale_percents = array_values($sale_percents);
        arsort($sale_percents);
        $filters['sale_percents'] = $sale_percents;

        //Get Genders For Filter
        $genders = $products_filter->pluck('sex')->toArray();
        $genders = array_filter(array_unique($genders));
        $filters['genders'] = $genders;
        
        $product_group_options = $this->productGroupOption_pivot
        ->select('product_option_id', 'product_option_group_id')
        ->whereIn('product_id', $product_ids)
        // ->get()
        ->pluck('product_option_group_id', 'product_option_id')
        ->toArray();

        if(!empty($product_group_options)){
            $option_groups = [];
            
            //Reverse array to group options with group id
            foreach ($product_group_options as $option_id => $group_id)
            {
                $option_groups[$group_id][] = $option_id;
            }
            // Make Filters Array 
            foreach ($option_groups as $key => $value)
            {
                $group = $this->productOptionGroup_model->select('id', 'name')
                ->where('id', $key)
                ->where('status', 'published');

                if($slug == 'parfum' || $slug == 'cosmetics' || $slug == 'sale'){
                    $group = $group->where('name', '!=', 'Color'); // This is an exception for burmunk cosmetics section
                }

                $group = $group->first();
                if($group){
                    $group = $group->toArray();

                    $options = $this->productOption
                    ->select('id', 'name')
                    ->whereIn('id', $value)
                    ->where('status', 'published')
                    ->orderBy('name', 'DESC')
                    ->get()
                    ->toArray();
                    if($options){
                        $filters['properties'][$key]['options'] = $options;
                        $filters['properties'][$key]['group_name'] = $group['name'];
                    }
                }
            }
        }

        return view($this->module.'.category', [
            'categories' => $categories,
            'filters' => isset($filters) ? $filters : null ,
            'resource' => isset($resource) ? $resource : null,
            'resource_name' => isset($show_by) ? $show_by : null,
            // 'products' => $products->paginate(18),
            'products' => request()->has('page') ? $products->paginate(18) : null,
        ]);
    }

    public function ajaxGetProducts($show_by, $model = null, $slug = null)
    {
        $products = $this->product->distinct()
        ->select('products.id', 'products.title', 'products.slug', 'products.thumbnail', 'products.price', 'products.sale_price','products.sale_percent', 'products.brand_id', 'products.order', 'products.sex', 'brands.title as brand_name', 'brands.slug as brand_slug','products.lang'
            // 'products.price as variation_price',
            // 'products.sale_price as variation_sale_price',
            // \DB::raw("MIN(variations.price) AS variation_price"), 
            // \DB::raw("MIN(variations.sale_price) AS variation_sale_price"),
        )
        ->join('brands', 'brands.id', '=', 'products.brand_id');
        // ->leftjoin('variations', 'products.id', '=', 'variations.product_id');
        // ->leftjoin('carts', 'products.id', '=', 'carts.product_id');
        
        $products = $products->join('categorisables', 'categorisables.categorisable_id', 'products.id')
        ->join('cm_categories', 'cm_categories.id', 'categorisables.category_id')
        // ->where('categorisables.categorisable_type', 'App\Models\Product');
        ->where('cm_categories.type', 'products');

        // $products = $products->leftjoin('carts', 'carts.product_id', '=', 'products.id')
        // ->where('carts.cart_type', 'wishlist');
        
        // if(auth()->check()){
        //     $products = $products->where('carts.user_id', auth()->id());
        // }else if($this->session_id){
        //     $products = $products->where('carts.session_id', $this->session_id); 
        // }
        // $products = $products->where('carts.cart_type', 'wishlist');
        // ->orderBy('title', 'ASC')
        // ->orderBy('order', 'DESC');

        if(request()->has('q') && request()->slug == 'search'  && !empty(request()->get('q'))){
            $show_by = 'title';   
        }
        switch ($show_by) {
            case 'brands':
                    if($model){
                        $resource = $model->select('id')
                        ->where('slug', $slug)
                        ->where('status', 'published')
                        ->where('lang', $this->lang)
                        ->first();

                        if(!$resource){
                            return abort(404);
                        }
                    }
                    $products = $products->where('products.brand_id', $resource->id);
                break;
            case 'category':
                if(!request()->has('cat') || empty(request()->get('cat'))){
                    $products = $products->where('cm_categories.slug', $slug);
                }
                break;
            case 'title':
                $brands_ids = \App\Models\Brand::select('id', 'title')->where($show_by, 'LIKE', '%'.request()->get('q').'%')
                ->where('status', 'published')
                ->where('lang', $this->lang)
                ->get()->pluck('id')->toArray();
                
                if(!empty($brands_ids)){
                    $products = $products->whereIn('products.brand_id', $brands_ids)
                    ->where('products.lang', $this->lang)
                    ->where('products.thumbnail', '<>', NULL)
                    ->where('products.thumbnail', '<>', 'NULL')
                    ->where('products.thumbnail', '<>', '')
                    ->orWhere('products.'.$show_by, 'LIKE', '%'.request()->get('q').'%');
                }else{
                    $products = $products->where('products.'.$show_by, 'LIKE', '%'.request()->get('q').'%');
                }
                break;
            case 'sale':
                $products = $products->where('products.sale_percent', '>', 0)->orderBy('sale_percent', 'DESC');
                break;
        }
        
        $products = $products->where('products.status', 'published')
        ->where('products.lang', $this->lang)
        ->where('products.thumbnail', '<>', NULL)
        ->where('products.thumbnail', '<>', 'NULL')
        ->where('products.thumbnail', '<>', '')
        ->orderBy('order', 'DESC')
        ->groupBy('products.id');


        // if(request()->has('cat') && !empty(request()->get('cat')) ){
        //     $products = $products->join('cm_categories as cat', 'categorisables.category_id', 'cat.id')
        //     ->whereIn('cat.id', request()->get('cat'));
        // }

        if(request()->has('sex')){
            $products = $products->whereIn('sex', request()->get('sex'));
        }

        if(request()->has('sale_percent')){
            $products = $products->whereIn('products.sale_percent', request()->get('sale_percent'));
        }

        if(request()->has('properties')){
            $filter_group_options = [];
            foreach (request()->get('properties') as $group_id => $options) {
                foreach ($options as $key => $option_id) {
                    $filter_group_options[] = $option_id;
                }
            }
            $products = $products->join('product_group_options', 'product_group_options.product_id', 'products.id')
            ->whereIn('product_option_id', $filter_group_options);
        }

        if(request()->has('cat')){
            $products = $products->whereIn('cm_categories.id', request()->get('cat'));
        }

        // dd($products);
        $html = view($this->module.'.components.list', [
            'products' => $products->paginate(18),
        ])->render();
        return response()->json([ 'status' => true, 'html' => $html ]);

    }

    public function getProducts($show_by, $model = null, $slug = null)
    {
        // return true;
        if($model){
            $resource = $model->select('id', 'title', 'slug', 'thumbnail')
            ->where('slug', $slug)
            ->where('status', 'published')
            ->where('lang', $this->lang)
            ->first();

            if(!$resource){
                return abort(404);
            }
        }

        // if(auth()->check()){
        //     $wishlist_user_statement = " AND carts.user_id = ".auth()->id();
        // }else if($this->session_id){
        //     $wishlist_user_statement = " AND carts.session_id =". $this->session_id; 
        // }else{
        //     $wishlist_user_statement = "";
        // }

        // $wishlist_statement = "(CASE WHEN (carts.qty > 0 AND carts.cart_type = 'wishlist' ".$wishlist_user_statement.") THEN 1 ELSE 0 END) as in_wishlist";

        $products = $this->product
        ->select('products.id', 'products.title', 'products.slug', 'products.thumbnail', 'products.price', 'products.sale_price','products.sale_percent', 'products.brand_id', 'products.order', 'products.sex', 'brands.title as brand_name', 'brands.slug as brand_slug',
            // 'products.price as variation_price',
            // 'products.sale_price as variation_sale_price',
            // \DB::raw("MIN(variations.price) AS variation_price"), 
            // \DB::raw("MIN(variations.sale_price) AS variation_sale_price"),
        )
        ->join('brands', 'brands.id', '=', 'products.brand_id');
        // ->leftjoin('variations', 'products.id', '=', 'variations.product_id');
        // ->leftjoin('carts', 'products.id', '=', 'carts.product_id');
        
        $products = $products->join('categorisables', 'categorisables.categorisable_id', 'products.id')
        ->join('cm_categories', 'cm_categories.id', 'categorisables.category_id')
        // ->where('categorisables.categorisable_type', 'App\Models\Product');
        ->where('cm_categories.type', 'products');

        // $products = $products->leftjoin('carts', 'carts.product_id', '=', 'products.id')
        // ->where('carts.cart_type', 'wishlist');
        
        // if(auth()->check()){
        //     $products = $products->where('carts.user_id', auth()->id());
        // }else if($this->session_id){
        //     $products = $products->where('carts.session_id', $this->session_id); 
        // }
        // $products = $products->where('carts.cart_type', 'wishlist');
        // ->orderBy('title', 'ASC')
        // ->orderBy('order', 'DESC');

        switch ($show_by) {
            case 'brands':
                    $products = $products->where('products.brand_id', $resource->id);
                break;
            case 'category':
                if(!request()->has('cat') || empty(request()->get('cat'))){
                    $products = $products->where('cm_categories.slug', $slug);
                }
                break;
            case 'title':
                $products = $products->where('products.'.$show_by, 'LIKE', '%'.$slug.'%');
                break;
            case 'sale':
                $products = $products->where('products.sale_percent', '>', 0)->orderBy('sale_percent', 'DESC');
                break;
        }
        
        $products = $products->where('products.status', 'published')
        ->where('products.lang', $this->lang)
        ->where('products.thumbnail', '<>', null)
        ->where('products.thumbnail', '<>', 'NULL')
        ->orderBy('order', 'DESC')
        ->groupBy('products.id');
        $products_filter = $products->get();
        $product_ids = $products_filter->pluck('id')->toArray();
        // dd($product_ids);
        
        //Register Empty Filters array
        $filters = [];
        //Get Sale Percents For Filter
        $sale_percents = $products_filter->pluck('sale_percent')->toArray(); 
        $sale_percents = array_filter(array_unique($sale_percents));
        $sale_percents = array_values($sale_percents);
        arsort($sale_percents);
        $filters['sale_percents'] = $sale_percents;

        //Get Genders For Filter
        $genders = $products_filter->pluck('sex')->toArray();
        $genders = array_filter(array_unique($genders));
        $filters['genders'] = $genders;
        
        $product_group_options = $this->productGroupOption_pivot
        ->select('product_option_id', 'product_option_group_id')
        ->whereIn('product_id', $product_ids)
        // ->get()
        ->pluck('product_option_group_id', 'product_option_id')
        ->toArray();
        // dd($product_group_options);
        if(!empty($product_group_options)){
            $option_groups = [];
            
            //Reverse array to group options with group id
            foreach ($product_group_options as $option_id => $group_id)
            {
                $option_groups[$group_id][] = $option_id;
            }
            // Make Filters Array 
            foreach ($option_groups as $key => $value)
            {
                $group = $this->productOptionGroup_model->select('id', 'name')
                    ->where('id', $key)
                    ->where('status', 'published')
                    ->first();
                if($group){
                    $group = $group->toArray();

                    $options = $this->productOption
                    ->select('id', 'name')
                    ->whereIn('id', $value)
                    ->where('status', 'published')
                    ->get()
                    ->toArray();

                    if($options){
                        $filters['properties'][$key]['options'] = $options;
                        $filters['properties'][$key]['group_name'] = $group['name'];
                    }
                }
            }
        }

        // Get Categories For Filter
        $categories = $this->category->distinct()
        ->select('cm_categories.id', 'cm_categories.title', 'cm_categories.slug', 'cm_categories.level', 'cm_categories.node', 'cm_categories.order', 'cm_categories.parent_id')
        ->join('categorisables','categorisables.category_id', 'cm_categories.id')
        ->where('cm_categories.type', 'products')
        // ->where('categorisables.categorisable_type', 'App\Models\Product')
        ->where('cm_categories.lang', $this->lang)
        ->where('status', 'published')
        ->whereIn('categorisables.categorisable_id', $product_ids)
        ->orderBy('level', 'ASC')
        ->orderBy('order', 'DESC')
        ->get()->groupBy('parent_id');

        // if(request()->has('cat') && !empty(request()->get('cat')) ){
        //     $products = $products->join('cm_categories as cat', 'categorisables.category_id', 'cat.id')
        //     ->whereIn('cat.id', request()->get('cat'));
        // }

        if(request()->has('sex')){
            $products = $products->whereIn('sex', request()->get('sex'));
        }

        if(request()->has('sale_percent')){
            $products = $products->whereIn('products.sale_percent', request()->get('sale_percent'));
        }

        if(request()->has('properties')){
            $filter_group_options = [];
            foreach (request()->get('properties') as $group_id => $options) {
                foreach ($options as $key => $option_id) {
                    $filter_group_options[] = $option_id;
                }
            }
            $products = $products->join('product_group_options', 'product_group_options.product_id', 'products.id')
            ->whereIn('product_option_id', $filter_group_options);
        }

        if(request()->has('cat')){
            $products = $products->whereIn('cm_categories.id', request()->get('cat'));
        }
        
        // $products = $products->paginate(18);
       
        if(request()->ajax()){
            $html = view($this->module.'.components.list', [
                'categories' => $categories,
                'filters' => $filters,
                'resource' => isset($resource) ? $resource : null,
                'resource_name' => isset($show_by) ? $show_by : null,
                'products' => $products->paginate(18),
            ])->render();

            return response()->json([ 'status' => true, 'html' => $html ]);
        }

        return view($this->module.'.category', [
            'categories' => $categories,
            'filters' => $filters,
            'resource' => isset($resource) ? $resource : null,
            'resource_name' => isset($show_by) ? $show_by : null,
            // 'products' => $products->paginate(18),
            'products' => request()->has('page') ? $products->paginate(18) : null,
        ]);
    }

    public function filter()
    {
        $model = null;
        if(request()->get('show_by') == 'category'){
            $model = $this->category;
        }elseif(request()->get('show_by') == 'brands'){
            $model = $this->brand;
        }
        if(request()->ajax()){
            return $this->ajaxGetProducts(request()->get('show_by'), $model, request()->get('slug'));
        }

        return $this->getProducts(request()->get('show_by'), $model, request()->get('slug'));

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $product = $this->product
        ->select('products.*', 'brands.title as brand_name', 'brands.slug as brand_slug', 
            \DB::raw("MIN(variations.price) AS variation_price"), 
            \DB::raw("MIN(variations.sale_price) AS variation_sale_price")
        )         
        ->join('brands', 'brands.id', 'products.brand_id')           
        ->leftjoin('variations', 'products.id', '=', 'variations.product_id')
        ->with(['gallery' => function($q){
            return $q->select('product_id', 'url', 'alt')->orderBy('sort', 'ASC');
        }])->with(['categories' => function($q){
            // return $q->select('product_id', 'url', 'alt')->orderBy('sort', 'ASC');
        }])

        // ->join('variations', 'variations.id', 'product.id')
        // ->with('variation_options')
        ->where('products.slug', $slug)
        ->where('products.status', 'published')
        ->where('products.lang', $this->lang)
        ->groupBy('products.id')
        ->first();


        if(!$product){
            return abort(404);
        }
        
        //Tracking page view
        $this->tracker([
            'resource' => 'product',
            'resource_id' => $product->id,
            'model' => get_class($product)
        ]);
        
        $options_groups = $this->get_product_grouped_options($product->id);
        $variations = $this->get_variations($product->id);
        
        $variations_options_grouped = null;
        
        if(!empty($variations)){
            $variations_options_grouped = $this->get_variations_options_grouped($product->id);
        }

        // dd($variations);
        $categories_id = $product->categories->pluck('id')->toArray();

        $related_products = $this->product
        // ->select('products.*')
        ->select('products.id', 'products.title', 'products.slug', 'products.thumbnail', 'products.price', 'products.sale_price', 'products.sale_percent')
        // ->with('groπ_options')
        // group_options
        // variation_options
        // variations
        ->join('categorisables', 'categorisables.categorisable_id', 'products.id')
        ->join('cm_categories', 'cm_categories.id', 'categorisables.category_id')
        // ->where('categorisables.categorisable_type', 'App\Models\Product')
        ->where('cm_categories.type', 'products')
        ->where('products.status', 'published')
        ->where('products.lang', $this->lang)
        ->whereIn('cm_categories.id', $categories_id)
        ->where('products.id', '!=', $product->id)
        ->where('products.thumbnail', '!=', null);
        
        $related_products_count = $related_products->count();
        if($related_products_count > 12){
            $related_products = $related_products->get()->random(12);
        }
        
        // FB Pixel tracking data for tracking ViewContent
        $fb_data = [
            'content_name' => $product->title,
            'content_ids' => array($product->id),
            'content_type' =>  'product',
            'value' => $product->sale_price && $product->sale_price > 0 ? $product->sale_price : $product->price,
            'currency' => 'AMD'
        ];
        $content_category = '';
        if(isset($product->categories) && !$product->categories->isEmpty()){
            foreach ($product->categories as $key => $cat) {
                if($key == 0){
                    $content_category .= $cat->title;
                }else{
                    $content_category .= ' > '. $cat->title;
                }
            }
        }
        $fb_data['content_category'] = $content_category;
        // End FB Pixel 

        return view($this->module.'.show', [
            'product' => $product,
            'options_groups' => $options_groups,
            'variations_options_grouped' => $variations_options_grouped,
            'related_products' => $related_products,
            'variations' => $variations,
            'fb_data' => json_encode($fb_data)
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function quick_view($id)
    {
        $product = $this->product
        ->with(['gallery' => function($q){
            return $q->select('product_id', 'url', 'alt')->orderBy('sort', 'ASC');
        }])->with(['categories' => function($q){
            // return $q->select('product_id', 'url', 'alt')->orderBy('sort', 'ASC');
        }])
        // ->join('variations', 'variations.id', 'product.id')
        // ->with('variation_options')
        ->where('id', $id)
        ->where('status', 'published')
        ->where('lang', $this->lang)
        ->first();

        if(!$product){
            return response()->json(['status' => false, 'message' => 'Product Not Found'], 404);
        }
        
        $options_groups = $this->get_product_grouped_options($product->id);
        $variations = $this->get_variations($product->id);
        
        $variations_options_grouped = null;
        
        if(!empty($variations)){
            $variations_options_grouped = $this->get_variations_options_grouped($product->id);
        }

        $categories_id = $product->categories->pluck('id')->toArray();

        //Tracking page view
        $this->tracker([
            'resource' => 'product',
            'resource_id' => $product->id,
            'model' => get_class($product)
        ]);
        
        $html = view($this->module.'.product_popup', [
            'product' => $product,
            'options_groups' => $options_groups,
            'variations_options_grouped' => $variations_options_grouped,
            'variations' => $variations,
        ])->render();

        return response()->json(['status' => true, 'html' => $html]);
    }


    public function getVariationPrice(Request $request)
    {
        if(isset($request->product_id) && isset($request->option_id))
        {
            $variation = $this->variation_model
            ->join('product_variation_options', 'product_variation_options.variation_id', '=', 'variations.id')
            ->where('product_variation_options.product_option_id', $request->option_id)
            ->where('product_variation_options.product_id', $request->product_id)
            ->first();
            if($variation){
                return response()->json([
                    'status' => true, 
                    'html' => view('products.components._price', ['item' => $variation])->render()
                ]);
            }
            return false;
        }
    }


    private function get_product_grouped_options($id)
    {
        $selectd_group_options = $this->productGroupOption_pivot
        ->select('product_option_id', 'product_option_group_id',  'product_option_groups.name')
        ->join('product_option_groups', 'product_option_groups.id', '=', 'product_group_options.product_option_group_id')
        ->where('product_id', $id)
        ->get()
        // ->groupBy('product_option_groups.name')
        ->pluck('product_option_group_id','product_option_id')

        ->toArray();
        
        

        if(!empty($selectd_group_options) && is_array($selectd_group_options)){
            $selected_groups_ids = array_unique(array_values($selectd_group_options)); 
            $selected_option_ids = array_unique(array_keys($selectd_group_options)); 

            $selected_groups = $this->productOptionGroup_model
            ->select('id', 'name', 'type')
            ->with(['productOptions' => function($q) use ($selected_option_ids){
                return $q->select('name', 'id', 'product_option_group_id')
                ->whereIn('id', $selected_option_ids)
                ->orderBy('name', 'ASC');
            }])
            ->where('status', 'published')
            // ->where('lang', $this->lang)
            ->whereIn('id', $selected_groups_ids)
            ->orderBy('order', 'ASC')
            // ->pluck('name', 'id')
            ->get();

            return $selected_groups; // product selected groups with options
        }
        return null;
        // dd($selected_groups);

    }

    private function get_variations($id)
    {
        $variations = $this->variation_model
        ->join('product_variation_options', 'product_variation_options.variation_id', '=', 'variations.id')
        ->where('variations.product_id', $id)
        ->get()
        // ->groupBy('id') // for multiple variation groups use group by "id"
        ->groupBy('product_option_id') //for single variation group use group by "product_option_group_id"
        ->toArray();

        return $variations;
    }

    private function get_variations_options_grouped($id)
    {
        $variations_options_grouped = $this->productGroupOption_pivot
        ->distinct()
        ->select(
            'product_options.name as option_name', 
            'product_options.value as option_value', 
            'product_option_groups.name as group_name', 
            'product_group_options.product_id', 
            'product_group_options.product_option_id', 
            'product_group_options.product_option_group_id'
        )
        ->join('product_option_groups', 'product_option_groups.id','=','product_group_options.product_option_group_id')
        ->join('product_options', 'product_options.id', '=', 'product_group_options.product_option_id')
        ->join('product_variation_options', 'product_options.id', '=', 'product_variation_options.product_option_id')

        ->where('product_group_options.product_id', $id)
        ->orderBy('product_options.name', 'ASC', SORT_REGULAR)
        ->get()
        ->groupBy('product_option_group_id')
        ->toArray();
        return $variations_options_grouped;
    }
}

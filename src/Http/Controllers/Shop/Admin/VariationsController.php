<?php

namespace Codeman\Admin\Http\Controllers\Shop\Admin;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Codeman\Admin\Models\Shop\Product;
use Codeman\Admin\Models\Shop\Variation;
// use Codeman\Admin\Models\Shop\ProductImages;
use Codeman\Admin\Models\Shop\ProductOption;
use Codeman\Admin\Models\Shop\ProductOptionGroup;
use Codeman\Admin\Models\Shop\ProductGroupOption;
use Codeman\Admin\Models\Shop\ProductVariationOption;
use Codeman\Admin\Models\Category;
use App\Models\Warehouse;

use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Services\CRUDService;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Http\Controllers\ProductsController;
use App\Models\Label;

class VariationsController extends Controller
{

    protected $languages;
    protected $def_lang;
    protected $module;

    public function __construct(Variation $variation)
    {
        $this->CRUD = new CRUDService($variation); //passing $product variable as a model parameter
        $this->variation = $variation;
        // $this->languages = Language::orderBy('order')->pluck('name','code')->toArray();
        $this->def_lang = Language::orderBy('order')->first();
        $this->module = 'variations';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Label $label_model)
    {
        // //TODO implement user permissions
        // if(!auth()->user()->can('create-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }

        $categories = Category::where([
            'lang'=> $this->def_lang->code,
            'type'=> 'products'
        ])->get()->groupBy('parent_id');

        $products = Product::all()->pluck('title', 'id')->toArray();

        $product_options_grouped = ProductOptionGroup::select('id', 'name')
        ->with('productOptions', function($q){
            $q->select('id','product_option_group_id', 'name', 'value');
        })
        ->where('lang', $this->def_lang->code)
        ->where('status', 'published')
        // ->orderBy('name', 'ASC')
        ->get()->toArray();

        $label_ids = $label_model
                ->select('id', 'name')
                ->orderBy('order', 'DESC')
                ->get()
                ->pluck('name', 'id')
                ->toArray();

        if(request()->ajax()){
            return $this->getProducts(request());
            // return $this->prepareVariationsCollection(); :TODO Narek
        }

        return view('admin-panel::shop.variations.index', [
            // 'resources' => $this->CRUD->getAll($this->module),
            'categories' => $categories,
            'products' => $products,
            'resources' => isset($resources) ? $resources : null,
            'model' => $this->variation,
            'module' => $this->module,
            'dates' => $this->getDatesOfResources($this->variation),
            'languages' => $this->languages,
            'data_filters' => config()->get('admin-data-filters') && config()->get('admin-data-filters')['variations'] ? config()->get('admin-data-filters')['variations'] : null,
            'data_filters_json' => config()->get('admin-data-filters') && config()->get('admin-data-filters')['variations'] ? json_encode(config()->get('admin-data-filters')['variations']) : null,
            'product_options_grouped' => $product_options_grouped,
            'labels' => $label_ids,
        ]);
    }

    //return JSON
    public function getVariations($request)
    {
        ## Read value
        $dataFilters = $request->has('filters') ? $request->get('filters') : null;

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');

        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnName = $columnIndex_arr[0]['column'];
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchQuery = $search_arr['value']; // Search value

        $relations = $request->get('relations');
        $productSearchValue = [];
        if($relations && isset($relations['product']) && !empty($relations['product'])){
            $productSearchValue = $relations['product'];
        }
        $categorySearchValue = [];
        if($relations && isset($relations['categories']) && !empty($relations['categories'])){
            $categorySearchValue = $relations['categories'];
        }
        $properties = [];
        if($relations && isset($relations['option_groups']) && !empty($relations['option_groups'])){
            $properties = $relations['option_groups'];
            // dd($properties);
        }
        // Total records
        $totalRecords = $this->variation->select('count(*) as allcount')->count();
        if($rowperpage == -1){
            $start = 0;
            $rowperpage = $totalRecords;
        }

        // Fetch records
        $records = $this->variation
            ->select('id', 'variations.product_id', 'variations.title', 'variations.price', 'variations.sale_price', 'variations.sku', 'variations.thumbnail', 'variations.status', 'variations.created_at', 'variations.updated_at', 'variations.order');

        if($categorySearchValue && !empty($categorySearchValue)){
            $records = $records->whereHas(
                'product', function($q) use ($categorySearchValue) {
                return $q->whereHas('categories', function($q) use ($categorySearchValue){
                    return $q->whereIn('cm_categories.id', $categorySearchValue);
                });
            }
            );
        }

        if($productSearchValue && !empty($productSearchValue)){
            $records = $records->whereHas(
                'product', function($q) use ($productSearchValue) {
                return $q->where('products.id', $productSearchValue);
            }
            );
        }

        $records = $records->with([
            'product' => function($q){
                $q->select('id', 'title')
                    ->orderBy('order', 'DESC')
                    ->orderBy('created_at', 'DESC')
                    ->with(['categories' => function($q){
                        return $q->select('cm_categories.id', 'cm_categories.title')
                            ->where('cm_categories.lang', $this->def_lang->code);
                    }
                    ]);
            },
            'options' => function($q){
                $q->with('productOptionGroup');
            }
        ]);

        if(isset($dataFilters) && is_array($dataFilters)){
            $records = $records->where(function($query) use($dataFilters) {
                foreach ($dataFilters as $field){
                    $field['value'] = trim($field['value']);
                    if(!empty($field['value'])){
                        $query->where($field['name'], 'like', "%{$field['value']}%");
                    }
                }
            });
        }

        if($properties && !empty($properties)){
            $properties_array = [];
            foreach ($properties as $key => $arr) {
                if(isset($arr['group_id']) && isset($arr['value'])){
                    $properties_array[$arr['group_id']] = $arr['value'];
                }
            }
            if(!empty($properties_array)){
                $filtered_variation_ids = $this->filterVariationsByProperties($properties_array, array());
                $records = $records->whereIn('variations.id', $filtered_variation_ids);
            }
        }

        if($rowperpage == -1){
            $start = 0;
            $rowperpage = $totalRecords;
        }
        $records = $records
            // ->where([ 'lang' => $this->def_lang->code ])
            ->orderBy($columnName, $columnSortOrder)
            ->orderBy('variations.product_id');
        $totalRecordswithFilter = $records->count();

        $records = $records
//            ->join('product_variation_options',
//                'product_variation_options.variation_id', '=', 'variations.id'
//            )
//            ->where('product_variation_options.product_option_group_id', 2)
//            ->groupBy([
//                'product_variation_options.product_option_id',
//                'product_variation_options.product_id'
//            ])
            ->skip($start)
            ->take($rowperpage)
            ->get()
            ->toArray();

        $response = array(
            "draw" => intval($draw),
            "iTotalRecords" => $totalRecords,
            "iTotalDisplayRecords" => $totalRecordswithFilter,
            "aaData" => $records
        );

        return response()->json($response);
    }

    //return JSON
    public function getProducts($request)
    {
        ## Read value
        $dataFilters = $request->has('filters') ? $request->get('filters') : null;

        $draw = $request->get('draw');
        $start = $request->get("start");
        $rowperpage = $request->get("length"); // Rows display per page

        $columnIndex_arr = $request->get('order');

        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        $columnName = $columnIndex_arr[0]['column'];
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchQuery = $search_arr['value']; // Search value

        $relations = $request->get('relations');
        $productSearchValue = [];
        if($relations && isset($relations['product']) && !empty($relations['product'])){
            $productSearchValue = $relations['product'];
        }
        $categorySearchValue = [];
        if($relations && isset($relations['categories']) && !empty($relations['categories'])){
            $categorySearchValue = $relations['categories'];
        }
        $properties = [];
        if($relations && isset($relations['option_groups']) && !empty($relations['option_groups'])){
            $properties = $relations['option_groups'];
            // dd($properties);
        }
        // Total records
        $totalRecords = $this->variation->select('count(*) as allcount')->count();
        if($rowperpage == -1){
            $start = 0;
            $rowperpage = $totalRecords;
        }

//        $records = $this->variation->prepareVariationsCollection()->get();

        // Fetch records
        $records = $this->variation
        ->select('id', 'variations.product_id', 'variations.title', 'variations.price', 'variations.sale_price', 'variations.sku', 'variations.thumbnail', 'variations.status', 'variations.created_at', 'variations.updated_at', 'variations.order');

        if($categorySearchValue && !empty($categorySearchValue)){
            $records = $records->whereHas(
                'product', function($q) use ($categorySearchValue) {
                    return $q->whereHas('categories', function($q) use ($categorySearchValue){
                        return $q->whereIn('cm_categories.id', $categorySearchValue);
                    });
                }
            );
        }

        if($productSearchValue && !empty($productSearchValue)){
            $records = $records->whereHas(
                'product', function($q) use ($productSearchValue) {
                    return $q->where('products.id', $productSearchValue);
                }
            );
        }

        $records = $records->with([
            'product' => function($q){
                $q->select('id', 'title')
                ->orderBy('order', 'DESC')
                ->orderBy('created_at', 'DESC')
                ->with(['categories' => function($q){
                        return $q->select('cm_categories.id', 'cm_categories.title')
                        ->where('cm_categories.lang', $this->def_lang->code);
                    }
                ]);
            },
            'inventories',
            'labels',
            'options' => function($q){
                $q->with('productOptionGroup');
            }
        ]);

        if(isset($dataFilters) && is_array($dataFilters)){
            $records = $records->where(function($query) use($dataFilters) {
                foreach ($dataFilters as $field){
                    $field['value'] = trim($field['value']);
                    if(!empty($field['value'])){
                        $query->where($field['name'], 'like', "%{$field['value']}%");
                    }
                }
            });
        }

        if($properties && !empty($properties)){
            $properties_array = [];
            foreach ($properties as $key => $arr) {
                if(isset($arr['group_id']) && isset($arr['value'])){
                    $properties_array[$arr['group_id']] = $arr['value'];
                }
            }
            if(!empty($properties_array)){
                $filtered_variation_ids = $this->filterVariationsByProperties($properties_array, array());
                $records = $records->whereIn('variations.id', $filtered_variation_ids);
            }
        }

        if($rowperpage == -1){
            $start = 0;
            $rowperpage = $totalRecords;
        }
        $records = $records
        // ->where([ 'lang' => $this->def_lang->code ])
        ->orderBy($columnName, $columnSortOrder)
        ->orderBy('variations.product_id');
        $totalRecordswithFilter = $records->count();

        $records = $records
        ->join('product_variation_options',
             'product_variation_options.variation_id', '=', 'variations.id'
        )
        ->where('product_variation_options.product_option_group_id', 2)
        ->groupBy([
             'product_variation_options.product_option_id',
             'product_variation_options.product_id'
         ]);

        $records_ids = $records
            ->get()
            ->pluck('id')
            ->toArray();
        $totalRecordswithFilter = count($records_ids);

        $records = $records
            ->skip($start)
            ->take($rowperpage)
            ->get();

        foreach($records as $item){
            $sizes_stock = $this->variation->getVariationSizesStock($item);
            $colorSizeStock = $this->variation->getVariationColorTotalStock($sizes_stock);
            $item->setAttribute('color_total_stock', $colorSizeStock);
        }

        $records = $records->toArray();

        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordswithFilter,
           "aaData" => $records
        );

        return response()->json($response);
    }

    /**
     * Generating Variations by provided product id
     *
     * @return \Illuminate\Http\Response
     */
    public function generateVariations($product_id, $type, ProductGroupOption $productGroupOption)
    {
        empty($type) ? "all" : $type;
        $options_count = $productGroupOption->where('product_id', $product_id)->count();

        $options_grouped = $productGroupOption
        ->select(
            'product_options.name as option_name',
            'product_option_groups.name as group_name',
            'product_group_options.product_id',
            'product_group_options.product_option_id',
            'product_group_options.product_option_group_id'
        )
        ->join('product_option_groups', 'product_option_groups.id','=','product_group_options.product_option_group_id')
        ->join('product_options', 'product_options.id', '=', 'product_group_options.product_option_id')
        ->where('product_id', $product_id)

        ->get()->groupBy('product_option_group_id')->toArray();

        $options_groups_count = count($options_grouped);

        $options_count = 1;
        foreach ($options_grouped as $key => $group) {
            $options_count = $options_count*count($group);
        }

        if($options_grouped){

            $html = view('admin-panel::shop.products.parts.variations.item',
                [
                    'options_count' => $options_count,
                    'options_grouped' => $options_grouped,
                    'options_groups_count' => $options_groups_count
                ]
            )->render();
            return response()->json(['status' => 'success', 'html' => $html]);
        }
        return response()->json(['status' => false]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(
        ProductOptionGroup $productOptionGroup_model,
        ProductGroupOption $productGroupOption_pivot,
        Label $label_model
    )
    {
        // if(!auth()->user()->can('edit-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }
        $model = $this->variation;
        if(null == $options = json_decode($model->options)){
            $options = [];
        }

        if(null == $relation_ids = json_decode($model->relations)){
            $slugs = [];
        }else{
            $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
        }

        $option_groups = $productOptionGroup_model
        ->where('lang', $this->def_lang->code)
        ->orderBy('name', 'ASC')
        ->pluck('name', 'id')
        ->all();

        $products = Product::where('lang', $this->def_lang->code)
        ->orderBy('title', 'ASC')
        ->pluck('title', 'id')
        ->all();

        $label_ids = $label_model
        ->select('id', 'name')
        ->orderBy('order', 'DESC')
        ->get()
        ->pluck('name', 'id')
        ->toArray();

        return view('admin-panel::shop.variations.create_edit', [
            'module' => $this->module,
            'options' => ['languages'],
            'additional_options' => [
                [
                    'id' => time()+1,
                    'type' => 'text',
                    'label' => 'SEO Title',
                    'name' => 'seo_title',
                    'info' => 'Enter a small SEO text for this product that will appear on the catalog page.',
                    'location' => 'default'
                ],
                [
                    'id' => time()+2,
                   'type' => 'select',
                   'input_type' => 'select',
                   'type_options' => [ 0 => 'Public Access', 1 => 'Private Access' ],
                   'label' => 'Access Type',
                   'name' => 'is_private',
                   'location' => 'right-sidebar',
                   'selected' => null
                ],
                [
                    'id' => time()+3,
                    'type' => 'image',
                    'label' => 'Photo By Model',
                    'name' => 'thumbnail',
                    'location' => 'right-sidebar'
                ],
                [
                    'id' => time()+4,
                    'type' => 'image',
                    'label' => 'Photo By Product',
                    'name' => 'secondary_thumbnail',
                    'location' => 'right-sidebar'
                ],
                [
                    'id' => time()+5,
                    'label' => 'Product Gallery',
                    'name' => 'images',
                    'type' => 'gallery-new',
                    'gallery' => null,
                    'location' => 'right-sidebar'
                ],
                [
                    'id' => time()+6,
                    'type' => 'text',
                    'label' => 'Video URL',
                    'name' => 'video_url',
                    'location' => 'right-sidebar'
                ],
                [
                    'id' => time()+7,
                    'type' => 'select',
                    'label' => 'Labels',
                    'name' => 'label_ids[]',
                    'type_options' => $label_ids,
                    'multiple' => true,
                    'location' => 'right-sidebar'
                ],
            ],
            'products' => $products,
            // 'relations' => $relations,
            'languages' => $this->languages,
            'order' => $this->CRUD->getMaxOrderNumber(),
            // 'categories' => $categories,
            // 'brands' => $brands,
            'option_groups' => $option_groups,
            'selected_groups' => $selected_groups ?? array(),
            'selectd_group_options' => !empty($selectd_group_options) ? array_keys($selectd_group_options) : array(),
            // 'attached_relations' => $attached_relations

            // 'options_grouped' => isset($variations_options_grouped) ? $variations_options_grouped : null,
            // 'options_count' => $variations_count,
            // 'variations' => $variations,
            // 'sex_group' => $sex_group,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request,
        ProductGroupOption $productGroupOption_pivot,
        Variation $variation_model,
        ProductVariationOption $productVariationOption_pivot,
        Product $product_model

    )
    {
        if (strpos($request->created_at, '/') !== false) {
            $request['created_at'] = Carbon::createFromFormat('d/m/Y', $request->created_at);
        }

        $product = $product_model->find($request->get('product_id'));
        if(!$product){
            return redirect()->back()->with('error', 'Product not found')->withInput($request->all());
        }

        $variation = $variation_model->firstOrCreate(
            [
                'sku' => $request->get('sku'),
                'product_id' => $request->get('product_id')
            ],
            $request->all()
        );

        if($request->has('group_options') && is_array($request->get('group_options'))){
            foreach ($request->get('group_options') as $group_id => $options) {
                foreach ($options as $key => $option_id) {
                    $productGroupOption_pivot->firstOrCreate(
                        [
                            'product_id' => $request->get('product_id'),
                            'product_option_id' => $option_id,
                            'product_option_group_id' => $group_id,
                        ]
                    );

                    $productVariationOption_pivot->firstOrCreate(
                        [
                            'variation_id' => $variation->id,
                            'product_option_group_id'  => $group_id,
                            'product_option_id'  => $option_id,
                            'product_id' => $request->get('product_id'),
                        ]
                    );
                }
            }
        }

        return redirect()->route($this->module.'.edit', $variation->id)->with('success',\Str::singular(ucwords($this->module))." Created Successfully.");
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, ProductOptionGroup $productOptionGroup_model, ProductGroupOption $productGroupOption_pivot, Label $label_model)
    {
        // if(!auth()->user()->can('edit-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }
        $model = $this->variation;
        if(null == $options = json_decode($model->options)){
            $options = [];
        }

        if(null == $relation_ids = json_decode($model->relations)){
            $slugs = [];
        }else{
            $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
        }

        // $resourcemetas = $decoded_resourcemetas;
        $resource = $this->get_with_relations($id);

        if(!$resource){
            return abort(404);
        }

        $option_groups = $productOptionGroup_model
        ->where('lang', $resource->lang ? $resource->lang : $this->def_lang->code)
        ->orderBy('name', 'ASC')
        ->pluck('name', 'id')
        ->all();

        // $selectd_group_options = $productGroupOption_pivot
        // ->select('product_option_id', 'product_option_group_id',  'product_option_groups.name')
        // ->join('product_option_groups', 'product_option_groups.id', '=', 'product_group_options.product_option_group_id')
        // ->where('product_id', $resource->product_id)
        // ->get()
        // // ->groupBy('product_option_groups.name')
        // ->pluck('product_option_group_id','product_option_id')

        // ->toArray();

        $selectd_group_options = [];
        foreach ($resource->options as $key => $options) {
            $selectd_group_options[$options->id] = $options->productOptionGroup->id;
        }

        if(!empty($selectd_group_options) && is_array($selectd_group_options)){
            $selected_groups_ids = array_unique(array_values($selectd_group_options));

            $selected_groups = $productOptionGroup_model
            ->select('id', 'name')
            ->with(['productOptions' => function($q){
                return $q->select('name', 'id', 'product_option_group_id');
            }])
            ->orderBy('order', 'ASC')
            ->whereIn('id', $selected_groups_ids)
            // ->pluck('name', 'id')
            ->get();
        }

        $label_ids = $label_model
        ->select('id', 'name')
        ->orderBy('order', 'DESC')
        ->get()
        ->pluck('name', 'id')
        ->toArray();

        return view('admin-panel::shop.variations.create_edit', [
            'resource' => $resource,
            'module' => $this->module,
            'options' => ['slug', 'languages'],
            'additional_options' => [
                [
                    'id' => time()+1,
                    'type' => 'text',
                    'label' => 'SEO Title',
                    'name' => 'seo_title',
                    'info' => 'Enter a small SEO text for this product that will appear on the catalog page.',
                    'location' => 'default'
                ],
                [
                   'type' => 'select',
                   'input_type' => 'select',
                   'type_options' => [ 0 => 'Public Access', 1 => 'Private Access' ],
                   'label' => 'Access Type',
                   'name' => 'is_private',
                   'location' => 'right-sidebar',
                   'selected' => null
                ],
                [
                    'id' => time()+2,
                    'type' => 'image',
                    'label' => 'Photo By Model',
                    'name' => 'thumbnail',
                    'location' => 'right-sidebar'
                ],
                [
                    'id' => time()+3,
                    'type' => 'image',
                    'label' => 'Photo By Product',
                    'name' => 'secondary_thumbnail',
                    'location' => 'right-sidebar'
                ],
                [
                    'id' => time()+4,
                    'label' => 'Product Gallery',
                    'name' => 'images',
                    'type' => 'gallery-new',
                    'gallery' => isset($resource) ? $resource->gallery : [],
                    'location' => 'right-sidebar'
                ],
                [
                    'id' => time()+5,
                    'type' => 'text',
                    'label' => 'Video URL',
                    'name' => 'video_url',
                    'location' => 'right-sidebar'
                ],
                [
                    'id' => time()+6,
                    'type' => 'select',
                    'label' => 'Labels',
                    'name' => 'label_ids[]',
                    'type_options' => $label_ids,
                    'multiple' => true,
                    'location' => 'right-sidebar'
                ],
            ],
            // 'relations' => $relations,
            'languages' => $this->languages,
            'order' => isset($resource) ? $resource->order : $this->CRUD->getMaxOrderNumber(),
            // 'categories' => $categories,
            // 'brands' => $brands,
            'option_groups' => $option_groups,
            'selected_groups' => $selected_groups ?? array(),
            'selectd_group_options' => !empty($selectd_group_options) ? array_keys($selectd_group_options) : array(),

            // 'attached_relations' => $attached_relations

            // 'options_grouped' => isset($variations_options_grouped) ? $variations_options_grouped : null,
            // 'options_count' => $variations_count,
            // 'variations' => $variations,
            // 'sex_group' => $sex_group,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id,
        ProductGroupOption $productGroupOption_pivot,
        Variation $variation_model,
        ProductVariationOption $productVariationOption_pivot,
        Product $product_model
    )
    {
        $variation = $variation_model->find($id);
        if(!$variation){
            return redirect()->back()->with('error', 'Variation not found')->withInput($request->all());
        }

        $sibling_variation_sizes_ids = $this->variation->getVariationRelatedSizesIds($variation);
        $sibling_variations = $variation_model->find($sibling_variation_sizes_ids);

        $request['product_id'] = $variation->product_id;
        $inputs = $this->updateVariationInputs($request->all());
        $variation->update($inputs);

        //Sync Gallery
        $image_ids = [];
        if(isset($request['images'])){
            if(isJson($request['images'])){
                $images = json_decode($request['images']);
                if(!empty($images)){
                    foreach ($images as $key => $image) {
                        $image_ids[$key]['image_id'] = $image->id;
                        $image_ids[$key]['alt'] = $image->alt;
                        $image_ids[$key]['type'] = 'gallery';
                    }
                }
            }
        }
        if(!$sibling_variations->isEmpty()){
            foreach ($sibling_variations as $key => $sibling) {
                $sibling->update([
                    'thumbnail' =>  $inputs['thumbnail'],
                    'secondary_thumbnail' => $inputs['secondary_thumbnail'],
                    'video_url' => $inputs['video_url'],
                    'seo_title' => $inputs['seo_title'],
                    'label_ids' => isset($inputs['label_ids']) ? $inputs['label_ids'] : null,
                    'price' => $inputs['price'],
                    'sale_price' => $inputs['sale_price'],
                    'is_private' => $inputs['is_private'],
                ]);
                $sibling->gallery()->sync($image_ids);
            }
        }else{
            $variation->gallery()->sync($image_ids);
        }

        $old_productGroupOption_pivot = $productGroupOption_pivot
            ->where('product_id', $request->get('product_id'))
            ->get()
            ->toArray();

        $old_productVariationOption_pivot = $productVariationOption_pivot
        ->where('product_id', $request->get('product_id'))
        ->where('variation_id', $variation->id)
        ->get()
        ->toArray();

        // $variation->thumbnail()->sync([[
        //     'image_id' => 1,
        //     'alt' => 'alt',
        //     'type' => 'thumbnail'
        // ]]);
        // dd($variation->gallery);

        // dd($old_productGroupOption_pivot);
        // ids
        // $oldIds = array_pluck($oldData, 'id');
        // $newIds = array_filter(array_pluck($newData, 'id'), 'is_numeric');
        $new_productGroupOption_pivot = [];
        $new_productVariationOption_pivot = [];

        if($request->has('group_options') && is_array($request->get('group_options'))){
            foreach ($request->get('group_options') as $group_id => $options) {
                foreach ($options as $key => $option_id) {
                    $new_productGroupOption_pivot[] = $productGroupOption_pivot->firstOrCreate([
                        'product_id' => $request->get('product_id'),
                        'product_option_id' => $option_id,
                        'product_option_group_id' => $group_id,
                    ])->toArray();

                    $new_productVariationOption_pivot[] = $productVariationOption_pivot->firstOrCreate([
                        'variation_id' => $variation->id,
                        'product_option_group_id'  => $group_id,
                        'product_option_id'  => $option_id,
                        'product_id' => $request->get('product_id'),
                    ])->toArray();
                }
            }
        }

        if(!empty($old_productVariationOption_pivot)){
            foreach ($old_productVariationOption_pivot as $key => $variation_option) {
                if(array_search($variation_option, $new_productVariationOption_pivot) === false){
                    $productVariationOption_pivot->where($variation_option)->delete();
                }
            }
        }

        return redirect()->route($this->module.'.edit', $variation->id)->with('success',\Str::singular(ucwords($this->module))." Updated Successfully.");
    }

    public function sort()
    {
        $categories = Category::where([
            'lang'=> $this->def_lang->code,
            'type'=> 'products'
        ])->get()->groupBy('parent_id');

        $category_ids = request()->has('category_id') ? request()->get('category_id') : [];
        $variations = $this->prepareVariationsCollection($category_ids)->get();
        return view('admin-panel::shop.variations.sort', compact('variations', 'categories'));
    }

    public function bulkEdit()
    {
        $update_data = [];
        if(request()->has('status') && !empty(request()->get('status'))){
            $update_data['status'] = request()->get('status');
        }
        if(request()->has('label_ids') && !empty(request()->get('label_ids'))){
            $update_data['label_ids'] = request()->get('label_ids');
        }
        $id_arr = explode(',', request()->get('ids'));

        $query = $this->variation->whereIn('id', $id_arr);
        $query->update($update_data);
    }

    public function loadModal($resource_id, Label $label_model)
    {
        $categories = Category::where([
            'lang'=> $this->def_lang->code,
            'type'=> 'products'
        ])->get()->groupBy('parent_id');

        $products = Product::all()->pluck('title', 'id')->toArray();

        $product_options_grouped = ProductOptionGroup::select('id', 'name')
        ->with('productOptions', function($q){
            $q->select('id','product_option_group_id', 'name', 'value');
        })
        ->where('lang', $this->def_lang->code)
        ->where('status', 'published')
        // ->orderBy('name', 'ASC')
        ->get()
        ->toArray();

        $label_ids = $label_model
        ->select('id', 'name')
        ->orderBy('order', 'DESC')
        ->get()
        ->pluck('name', 'id')
        ->toArray();

        $html = view('admin-panel::shop.variations.components._modal_variations', [
            'categories' => $categories,
            'products' => $products,
            'dates' => $this->getDatesOfResources($this->variation),
            'product_options_grouped' => $product_options_grouped,
            'labels' => $label_ids,
            'resource_id' => $resource_id
        ])->render();

        return response()->json(['status' => true, 'html' => $html]);
    }

    public function loadProductsAjax()
    {
        if(request()->ajax()){
            return $this->getProducts(request());
            // return $this->prepareVariationsCollection(); TODO Narek
        }
    }

    public  function loadaVariationsAjax()
    {
        if(request()->ajax()){
            return $this->getVariations(request());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $variation = $this->variation->find($id);
        if($variation){
            $variation_options = $variation->variation_options()->delete();
            $variation->delete();
            if(request()->ajax()){
                return response()->json(['status' => 'success']);
            }
            return redirect()->route($this->module.'.index')->with('success',\Str::singular(ucwords($this->module))." Successfully Deleted.");
        }
        if(request()->ajax()){
            return response()->json(['status' => false]);
        }
        return redirect()->route($this->module.'.index')->with('success',\Str::singular(ucwords($this->module))." Not Found.");
    }

    private function get_with_relations($id, $type = null)
    {

        return  $this->variation->where('id', $id)
        ->with([
            'labels',
            'options' => function($q){
                $q->with(['productOptionGroup' => function($q){

                }]);
            },
            'inventories' => function($q){
                $q->with(['warehouse'])->orderBy('warehouse_id', 'ASC');
            },

        ])
        ->first();
    }

    private function updateVariationInputs($request)
    {
        $request['label_ids'] = isset($request['label_ids']) ? $request['label_ids'] : null;
        return $request;
    }

    private function prepareVariationsCollection($categories = [], $properties = null)
    {
        $additional_filters = [];
        $variations = $this->variation
        ->select(
            'variations.id',
            'variations.product_id',
            'variations.title',
            'variations.price',
            'variations.sale_price',
            'variations.thumbnail',
            'variations.order',
            'variations.status',
            'variations.label_ids'
        )
        ->join('categorisables', 'categorisables.categorisable_id', 'variations.product_id')
        ->join('cm_categories', 'cm_categories.id', 'categorisables.category_id')
        ->where('cm_categories.type', 'products')
        ->join('product_variation_options',
            'product_variation_options.variation_id', '=', 'variations.id'
        )
        ->whereHas('product', function($q){
            $q->where('status', 'published');
        })
        ->with([
            'options',
            'product' => function($q){
                $q->select('id', 'title', 'status');
            },
        ]);

        if($categories && !empty($categories)){
            $variations = $variations->whereIn('cm_categories.id', $categories);
            $additional_filters['categories'] = $categories;
        }

        if($properties && !empty($properties)){
            // $filtered_variation_ids = $this->filterVariationsByProperties($properties, array(), $additional_filters);
            // $variations = $variations->whereIn('variations.id', $filtered_variation_ids);
        }

        $variations = $variations
        ->where('product_variation_options.product_option_group_id', 2)
        ->where('variations.status', 'published')
        ->orderBy('variations.order', 'DESC')
        ->groupBy([
            'product_variation_options.product_option_id',
            'product_variation_options.product_id'
        ]);

        return $variations;
    }

    private function filterVariationsByProperties($properties, $variation_ids = [],  $additional_filters = [])
    {
        foreach ($properties as $group_id => $options_arr) {
            $variations = $this->variation
            ->select(
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
}

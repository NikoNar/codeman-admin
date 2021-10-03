<?php

namespace Codeman\Admin\Http\Controllers\Shop\Admin;

// use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Codeman\Admin\Models\Shop\Product;
use Codeman\Admin\Models\Shop\ProductImages;
use Codeman\Admin\Models\Shop\ProductOption;
use Codeman\Admin\Models\Shop\ProductOptionGroup;
use Codeman\Admin\Models\Shop\ProductGroupOption;
use Codeman\Admin\Models\Shop\Variation;
use Codeman\Admin\Models\Shop\ProductVariationOption;
use Codeman\Admin\Models\Shop\Brand;

use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Category;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Services\CRUDService;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Codeman\Admin\Http\TranslitSlug;

class ProductsController extends Controller
{

    protected $languages;
    protected $def_lang;
    protected $module;

    public function __construct(Product $product)
    {
        $this->CRUD = new CRUDService($product); //passing $product variable as a model parameter
        $this->product = $product;
        $this->languages = Language::orderBy('order')->pluck('name','code')->toArray();
        $this->def_lang = Language::orderBy('order')->first();
        $this->module = 'products';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // dd(request()->all());
        // //TODO implement user permissions
        // if(!auth()->user()->can('create-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }
        $categories = Category::where([
            'lang'=> $this->def_lang->code,
            'type'=> $this->module
        ])->get()->groupBy('parent_id');
        
        $product_options_grouped = ProductOptionGroup::select('id', 'name')
        ->with('productOptions', function($q){
            $q->select('id','product_option_group_id', 'name', 'value');
        })
        ->where('lang', $this->def_lang->code)
        ->where('status', 'published')
        // ->orderBy('name', 'ASC')
        ->get()->toArray();

        if(request()->ajax()){
            return $this->getProducts(request());
        }

        return view('admin-panel::shop.products.index', [
            // 'resources' => $this->CRUD->getAll($this->module), 
            'categories' => $categories,
            'resources' => isset($resources) ? $resources : null, 
            'module' => $this->module, 
            'dates' => $this->getDatesOfResources($this->product), 
            'languages' => $this->languages,
            'data_filters' => config()->get('admin-data-filters') && config()->get('admin-data-filters')['products'] ? config()->get('admin-data-filters')['products'] : null,
            'data_filters_json' => config()->get('admin-data-filters') && config()->get('admin-data-filters')['products'] ? json_encode(config()->get('admin-data-filters')['products']) : null,
            'product_options_grouped' => $product_options_grouped,
        ]);
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
        // dd($columnIndex_arr);
        $columnName_arr = $request->get('columns');
        $order_arr = $request->get('order');
        $search_arr = $request->get('search');

        // $columnIndex = $columnIndex_arr[0]['column']; // Column index
        // $columnName = $columnName_arr[$columnIndex]['data']; // Column name
        $columnName = $columnIndex_arr[0]['column'];
        $columnSortOrder = $order_arr[0]['dir']; // asc or desc
        $searchQuery = $search_arr['value']; // Search value

        $relations = $request->get('relations');
        $categorySearchValue = [];
        if($relations && isset($relations['categories']) && !empty($relations['categories'])){
            $categorySearchValue = $relations['categories'];
        }

        $properties = [];
        if($relations && isset($relations['option_groups']) && !empty($relations['option_groups'])){
            $properties = $relations['option_groups'];
        }

        // Total records
        $totalRecords = $this->product->select('count(*) as allcount')->count();
        if($rowperpage == -1){
            $start = 0;
            $rowperpage = $totalRecords;
        }

        // Fetch records
        $records = $this->product
        ->select('id', 'title', 'slug', 'price', 'sale_price', 'sku', 'thumbnail', 'status', 'lang', 'created_at', 'updated_at', 'order');

        if($categorySearchValue && !empty($categorySearchValue)){
            $records = $records->whereHas(
                'categories', function($q) use ($categorySearchValue) {
                    return $q->whereIn('cm_categories.id', $categorySearchValue);
                    // ->where('cm_categories.lang', $this->def_lang->code);
                }
            );
        }
        if($properties && !empty($properties)){
            $records = $records->whereHas(
                'variation_options', function($q) use ($properties){
                    return $q->where(function($q) use ($properties){
                        foreach ($properties as $key => $property) {
                            if(isset($property['value']) && !empty($property['value'])){
                                $q->whereIn('product_variation_options.product_option_id', $property['value']);
                            }
                        }
                    });
                }
            );
        }

        $records = $records->with([
            'categories' => function($q){
                return $q->select('cm_categories.id', 'cm_categories.title')
                ->where('cm_categories.lang', $this->def_lang->code);
            }  
        ]);

        $records = $records->with([
            'variations' => function($q){
                $q->select('id', 'title', 'sku', 'product_id', 'price', 'sale_price', 'thumbnail', 'stock_count', 'order', 'status')->orderBy('order', 'DESC')->orderBy('created_at', 'DESC');
            }
        ]);

        $records = $records->with([
            'variation_options' => function($q) use ($properties){
                foreach ($properties as $key => $property) {
                    if(isset($property['value']) && !empty($property['value'])){
                        $q->whereIn('product_option_id', $property['value']);
                    }
                }
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

        if($rowperpage == -1){
            $start = 0;
            $rowperpage = $totalRecords;
        }
        $records = $records->where([ 'lang' => $this->def_lang->code ])
        ->orderBy($columnName, $columnSortOrder);
        $totalRecordswithFilter = $records->count();
        // dd($records->dd());
        $records = $records->skip($start)
        ->take($rowperpage)
        ->get()
        ->toArray();
        // dd($records);
        
        $response = array(
           "draw" => intval($draw),
           "iTotalRecords" => $totalRecords,
           "iTotalDisplayRecords" => $totalRecordswithFilter,
           "aaData" => $records
        );

        return response()->json($response);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create( Brand $brand_model, ProductOptionGroup $productOptionGroup_model )
    {
        // //TODO implement user permissions
        // if(!auth()->user()->can('create-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }

        $brands = $brand_model
        ->where('lang', $this->def_lang->code)
        ->orderBy('title', 'ASC')
        ->pluck('title', 'id')
        ->all();

        $option_groups = $productOptionGroup_model
        ->where('lang', $this->def_lang->code)
        ->orderBy('name', 'ASC')
        ->pluck('name', 'id')
        ->all();

        $categories = Category::where([
            'lang'=> $this->def_lang->code,
            'type'=> $this->module
        ])->get()->groupBy('parent_id');

        $sex_group = $this->product->distinct()->select('sex')->get()->pluck('sex', 'sex')->toArray();
        
        return view('admin-panel::shop.products.create', [
            'order' => $this->CRUD->getMaxOrderNumber(),
            'module' => $this->module,
            'options' => ['slug', 'ckeditor', 'languages', 'categories', 'thumbnail'],
            'relations' => null,
            'additional_options' => [
                // [
                //     'type' => 'image',
                //     'label' => 'Secondary Image',
                //     'name' => 'metas[secondary_thumbnail]',
                //     'location' => 'right-sidebar',
                //     'value' => isset($resource_metas['secondary_thumbnail']) ? $resource_metas['secondary_thumbnail'] : null
                // ],
                // [
                //     'id' => time()+1, 
                //     'label' => 'Product Gallery', 
                //     'name' => 'images', 
                //     'type' => 'gallery',
                //     'gallery' => isset($resource) ? $resource->gallery()->get()->toArray() : [],
                //     'location' => 'right-sidebar'
                // ],
                [
                     'type' => 'textarea',
                     'label' => 'Уход За Одеждой',
                     'name' => 'metas[УходЗаОдеждой]',
                     'location' => 'default',
                     'value' => isset($resource_metas['УходЗаОдеждой']) ? $resource_metas['УходЗаОдеждой'] : null
                ],
                [
                     'type' => 'textarea',
                     'label' => 'Дополнительное Описание',
                     'name' => 'metas[ДополнительноеОписание]',
                     'location' => 'default',
                     'value' => isset($resource_metas['ДополнительноеОписание']) ? $resource_metas['ДополнительноеОписание'] : null
                ],
                [
                     'type' => 'textarea',
                     'label' => 'Состав Одежды',
                     'name' => 'metas[СоставРУ]',
                     'location' => 'default',
                     'value' => isset($resource_metas['СоставРУ']) ? $resource_metas['СоставРУ'] : null
                ],

                // [
                //      'type' => 'input',
                //      'label' => 'Сезон',
                //      'name' => 'metas[Сезон]',
                //      'location' => 'default',
                //      'value' => isset($resource_metas['Сезон']) ? $resource_metas['Сезон'] : null
                // ],
                
                [
                     'type' => 'datetimepicker',
                     'label' => 'Планируемая дата поступления',
                     'name' => 'metas[Планируемая дата поступления]',
                     'location' => 'default',
                     'value' => isset($resource_metas['Планируемая дата поступления']) ? $resource_metas['Планируемая дата поступления'] : null
                ],
                
                [
                     'type' => 'input',
                     'label' => 'Код',
                     'name' => 'metas[Код]',
                     'location' => 'default',
                     'value' => isset($resource_metas['Код']) ? $resource_metas['Код'] : null
                ],
                
                [
                     'type' => 'input',
                     'label' => 'Полное наименование',
                     'name' => 'metas[Полное наименование]',
                     'location' => 'default',
                     'value' => isset($resource_metas['Полное наименование']) ? $resource_metas['Полное наименование'] : null
                ],
                
                [
                     'type' => 'input',
                     'label' => 'Тип Номенклатуры',
                     'name' => 'metas[ТипНоменклатуры]',
                     'location' => 'default',
                     'value' => isset($resource_metas['ТипНоменклатуры']) ? $resource_metas['ТипНоменклатуры'] : null
                ],
                
                [
                     'type' => 'input',
                     'label' => 'Вид Номенклатуры',
                     'name' => 'metas[ВидНоменклатуры]',
                     'location' => 'default',
                     'value' => isset($resource_metas['ВидНоменклатуры']) ? $resource_metas['ВидНоменклатуры'] : null
                ],
            ],
            'languages' => $this->languages,
            'categories'=> $categories,
            // 'brands' => $brands,
            'option_groups' => $option_groups,
            'sex_group' => $sex_group,
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, ProductGroupOption $productGroupOption_pivot)
    {
        // //TODO implement user permissions
        // if(!auth()->user()->can('create-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }

        if(!$request->has('lang')){
            $request['lang'] = $this->def_lang->code;
        }

        if($resource = $this->CRUD->store($request->all())){
            // if($request->has('meta'))
            // {
            //     $this->CRUD->createUpdateMeta($resource->id, $request->get('meta'));
            // }
            // if($request->relations){
            //     $relations = (array) json_decode($request->relations, true);
            //     $resource->relations()->sync($relations);
            // }
            $request_metas = $request->has('metas') ? $request['metas'] : [];
            foreach($request_metas as $meta_key => $meta_value){
                $resource->metas()->insert([
                    'product_id' => $resource->id,
                    'key' => $meta_key,
                    'value' => $meta_value
                ]);
            }

            //Product Gallery
            if($request->images){
                $gallery_images = (array) json_decode($request->images, true);
                $resource->gallery()->delete();
                
                //sorting and saving images 
                foreach ($gallery_images as $key => $image) {
                    $image['sort'] = $key;
                    $resource->gallery()->create($image);
                }
            }

            if($request->has('group_options') && is_array($request->get('group_options'))){
                foreach ($request->get('group_options') as $group_id => $options) {
                    foreach ($options as $key => $option_id) {
                        $productGroupOption_pivot->firstOrCreate(
                            [
                                'product_id' => $resource->id,
                                'product_option_id' => $option_id,
                                'product_option_group_id' => $group_id,
                            ]
                        );
                    }
                }
            }

            return redirect()->route('products.edit', $resource->id)->with('success',\Str::singular(ucwords($this->module))." Created Successfully.");
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id, Brand $brand_model, 
        ProductOptionGroup $productOptionGroup_model,
        ProductGroupOption $productGroupOption_pivot,
        Variation $variation_model)
    {

        // $unicodeSlugClass =  new TranslitSlug();
        // dd($unicodeSlugClass->build('Привет Друган', '-', 2, false));
        // //TODO implement user permissions
        
        // if(!auth()->user()->can('edit-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }
        $model = $this->product;
        if(null == $options =json_decode($model->options)){
            $options = [];
        }

        if(null == $relation_ids = json_decode($model->relations)){
            $slugs = [];
        }else{
            $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
        }

        // $resourcemetas = $decoded_resourcemetas;
        $resource = $this->get_with_relations($id);
        $resource_metas = $resource->metas->pluck('value', 'key')->toArray();
        if(!$resource){
            return abort(404);
        }

        // $brands = $brand_model
        // ->where('lang', $resource->lang)
        // ->orderBy('title', 'ASC')
        // ->pluck('title', 'id')
        // ->all();

        $option_groups = $productOptionGroup_model
        ->where('lang', $resource->lang)
        ->orderBy('name', 'ASC')
        ->pluck('name', 'id')
        ->all();

        $selectd_group_options = $productGroupOption_pivot
        ->select('product_option_id', 'product_option_group_id',  'product_option_groups.name')
        ->join('product_option_groups', 'product_option_groups.id', '=', 'product_group_options.product_option_group_id')
        ->where('product_id', $id)
        ->get()
        // ->groupBy('product_option_groups.name')
        ->pluck('product_option_group_id','product_option_id')

        ->toArray();

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

        $variations = $variation_model
        ->join('product_variation_options', 'product_variation_options.variation_id', '=', 'variations.id')
        ->where('variations.product_id', $id)
        ->get()
        ->groupBy('id')
        ->toArray();

        $variations_count = $variation_model->where('variations.product_id', $id)->count();
        

        if(!empty($variations)){
            $variations_options_grouped = $productGroupOption_pivot
            ->distinct()
            ->select(
                'product_options.name as option_name', 
                'product_option_groups.name as group_name', 
                'product_group_options.product_id', 
                'product_group_options.product_option_id', 
                'product_group_options.product_option_group_id'
            )
            ->join('product_option_groups', 'product_option_groups.id','=','product_group_options.product_option_group_id')
            ->join('product_options', 'product_options.id', '=', 'product_group_options.product_option_id')
            ->join('product_variation_options', 'product_options.id', '=', 'product_variation_options.product_option_id')

            ->where('product_group_options.product_id', $id)

            ->get()->groupBy('product_option_group_id')->toArray();
        }

        $categories = Category::where([
            'lang' => $resource->lang,
            'type'=>$this->module
        ])->get()->groupBy('parent_id');

        $sex_group = $this->product->distinct()->select('sex')->get()->pluck('sex', 'sex')->toArray();

        return view('admin-panel::shop.products.edit', [ 
            'resource' => $resource, 
            'module' => $this->module, 
            'options' => ['slug', 'ckeditor', 'languages', 'categories', 'thumbnail'],
            'additional_options' => [
                // [
                //     'type' => 'image',
                //     'label' => 'Secondary Image',
                //     'name' => 'metas[secondary_thumbnail]',
                //     'location' => 'right-sidebar',
                //     'value' => isset($resource_metas['secondary_thumbnail']) ? $resource_metas['secondary_thumbnail'] : null
                // ],
                // [
                //     'id' => time()+1, 
                //     'label' => 'Product Gallery', 
                //     'name' => 'images', 
                //     'type' => 'gallery',
                //     'gallery' => isset($resource) ? $resource->gallery()->get()->toArray() : [],
                //     'location' => 'right-sidebar'
                // ],
                [
                     'type' => 'textarea',
                     'label' => 'Уход За Одеждой',
                     'name' => 'metas[УходЗаОдеждой]',
                     'location' => 'default',
                     'value' => isset($resource_metas['УходЗаОдеждой']) ? $resource_metas['УходЗаОдеждой'] : null
                ],
                [
                     'type' => 'textarea',
                     'label' => 'Дополнительное Описание',
                     'name' => 'metas[ДополнительноеОписание]',
                     'location' => 'default',
                     'value' => isset($resource_metas['ДополнительноеОписание']) ? $resource_metas['ДополнительноеОписание'] : null
                ],
                [
                     'type' => 'textarea',
                     'label' => 'Состав Одежды',
                     'name' => 'metas[СоставРУ]',
                     'location' => 'default',
                     'value' => isset($resource_metas['СоставРУ']) ? $resource_metas['СоставРУ'] : null
                ],

                // [
                //      'type' => 'input',
                //      'label' => 'Сезон',
                //      'name' => 'metas[Сезон]',
                //      'location' => 'default',
                //      'value' => isset($resource_metas['Сезон']) ? $resource_metas['Сезон'] : null
                // ],
                
                [
                     'type' => 'datetimepicker',
                     'label' => 'Планируемая дата поступления',
                     'name' => 'metas[Планируемая дата поступления]',
                     'location' => 'default',
                     'value' => isset($resource_metas['Планируемая дата поступления']) ? $resource_metas['Планируемая дата поступления'] : null
                ],
                
                [
                     'type' => 'input',
                     'label' => 'Код',
                     'name' => 'metas[Код]',
                     'location' => 'default',
                     'value' => isset($resource_metas['Код']) ? $resource_metas['Код'] : null
                ],
                
                [
                     'type' => 'input',
                     'label' => 'Полное наименование',
                     'name' => 'metas[Полное наименование]',
                     'location' => 'default',
                     'value' => isset($resource_metas['Полное наименование']) ? $resource_metas['Полное наименование'] : null
                ],
                
                [
                     'type' => 'input',
                     'label' => 'Тип Номенклатуры',
                     'name' => 'metas[ТипНоменклатуры]',
                     'location' => 'default',
                     'value' => isset($resource_metas['ТипНоменклатуры']) ? $resource_metas['ТипНоменклатуры'] : null
                ],
                
                [
                     'type' => 'input',
                     'label' => 'Вид Номенклатуры',
                     'name' => 'metas[ВидНоменклатуры]',
                     'location' => 'default',
                     'value' => isset($resource_metas['ВидНоменклатуры']) ? $resource_metas['ВидНоменклатуры'] : null
                ],
            ],
            // 'relations' => $relations,
            'languages' => $this->languages, 
            'order' => isset($resource) ? $resource->order : $this->CRUD->getMaxOrderNumber(),
            'categories' => $categories,
            // 'brands' => $brands,
            'option_groups' => $option_groups,
            'selected_groups' => $selected_groups ?? array(),
            'selectd_group_options' => !empty($selectd_group_options) ? array_keys($selectd_group_options) : array(),
            // 'attached_relations' => $attached_relations 

            'options_grouped' => isset($variations_options_grouped) ? $variations_options_grouped : null,
            // 'options_count' => $variations_count,
            'variations' => $variations,
            'sex_group' => $sex_group,
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
        ProductVariationOption $productVariationOption_pivot)
    {
        // //TODO implement user permissions
        // if(!auth()->user()->can('edit-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }

        if (strpos($request->created_at, '/') !== false) {
            $request['created_at'] = Carbon::createFromFormat('d/m/Y', $request->created_at);
        }

        $resource = $this->product->find($id);
        
        //Product Meta Datas
        $metas = $resource->metas;
        $request_metas = $request->has('metas') ? $request['metas'] : [];
        foreach($metas as $key => $meta){
            if( isset($request_metas[$meta->key]) ){
                $is_updated = $meta->update(['value' => $request['metas'][$meta->key] ]);
                unset($request_metas[$meta->key]);
            }else{
                $meta->delete();
            }
        }
        foreach($request_metas as $meta_key => $meta_value){
            $resource->metas()->insert([
                'product_id' => $resource->id,
                'key' => $meta_key,
                'value' => $meta_value
            ]);
        }
        
        if($this->updateProduct($resource, $request->all())) {

            //Product Gallery
            if($request->images){
                $gallery_images = (array) json_decode($request->images, true);
                $resource->gallery()->delete();
                //sorting and saving images 
                foreach ($gallery_images as $key => $image) {
                    $image['sort'] = $key;
                    $resource->gallery()->create($image);
                }
            }else{
                $resource->gallery()->delete();
            }

            if($request->lang == $this->def_lang->code){
                $product_langs = $this->product->where('parent_lang_id', $resource->id)->get();
                if($product_langs){
                    $this->product->where('parent_lang_id', $resource->id)->update([
                        'thumbnail' => $request['thumbnail'],
                        'promortion_type' => $request['promortion_type'],
                        'sex' => $request['sex'],
                    ]);
                    //CREATE/UPDATE/DELET Product Translations Gallery
                    if($request->images){
                        $gallery_images = (array) json_decode($request->images, true);

                        if($product_langs && !$product_langs->isEmpty()){
                            foreach ($product_langs as $key => $product_translation) {
                                $product_translation->gallery()->delete();
                                //sorting and saving images 
                                foreach ($gallery_images as $key => $image) {
                                    $image['sort'] = $key;
                                    $product_translation->gallery()->create($image);
                                }
                            }
                        }

                    }else{
                        if($product_langs && !$product_langs->isEmpty()){
                            foreach ($product_langs as $key => $product_translation) {
                                $product_translation->gallery()->delete();
                            }
                        }
                    }
                }

                $product_langs_ids = $product_langs->pluck('id')->toArray();
            }

            $productGroupOption_pivot->where('product_id', $id)->delete();

            if($request->has('group_options') && is_array($request->get('group_options'))){
                foreach ($request->get('group_options') as $group_id => $options) {
                    foreach ($options as $key => $option_id) {
                        $productGroupOption_pivot->firstOrCreate(
                            [
                                'product_id' => $id,
                                'product_option_id' => $option_id,
                                'product_option_group_id' => $group_id,
                            ]
                        );
                    }
                }
            }

            if($request->has('variation') && is_array($request->get('variation'))){
                foreach ($request->get('variation') as $key => $variation) {                    
                    $variation['product_id'] = $id;
                    $new_variation = $variation_model->updateOrCreate(['id' => $variation['id']], $variation);
                    
                    if($request->lang == $this->def_lang->code){
                        $variation_langs = $variation_model->where('sku', $new_variation->sku)->whereIn('product_id', $product_langs_ids)->update(['thumbnail' => $new_variation->thumbnail]);
                    }
                    
                    foreach ($variation['options'] as $group_id => $option_id) {
                        $productVariationOption_pivot->firstOrCreate(
                            [
                                'variation_id' => $new_variation->id,
                                'product_option_group_id'  => $group_id,
                                'product_option_id'  => $option_id,
                                'product_id' => $id,
                            ]
                        );
                    }
                }
            }

            return redirect()->route('products.edit',$id)->with('success', \Str::singular(ucwords($this->module)) . " Successfully Updated.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // if(!auth()->user()->can('delete-'.$this->module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }

        $resource = $this->product->find($id);
        
        if($resource){
            // Find cascade delete and detach of relations on Codeman\Admin\Models\Shop::boot()
            $resource->delete();
            return redirect()->back()
            ->with('success', \Str::singular(ucwords($this->module)).' Successfully Deleted.');
        }
        return redirect()->back();
    }


    public function translate( $id, $lang,  
        Brand $brand_model, 
        ProductOptionGroup $productOptionGroup_model,
        ProductGroupOption $productGroupOption_pivot,
        Variation $variation_model )
    {
        // if(!auth()->user()->can('update-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }
        $translate = $this->createOrEditResourceTranslation($this->product, $this->module, $id, $lang );

        if(is_array($translate) && isset($translate['status']) && $translate['status'] == 'redirect'){
            return redirect($translate['route']);
        }

        if (isset($translate) && $translate->parent_lang_id != null) {
            $parent_lang_id = null;
        } else {
            $parent_lang_id = $translate->id;
        }
        
        if ($translate) {

            $categories = Category::where([
                'lang' => $translate->lang,
                'type' =>$this->module
            ])->get()->groupBy('parent_id');

            // $brands = $brand_model
            // ->where('lang', $lang)
            // ->orderBy('title', 'ASC')
            // ->pluck('title', 'id')
            // ->all();

            $option_groups = $productOptionGroup_model
            ->where('lang', $lang)
            ->orderBy('name', 'ASC')
            ->pluck('name', 'id')
            ->all();

            $selectd_group_options = $productGroupOption_pivot
            ->select('product_option_id', 'product_option_group_id',  'product_option_groups.name')
            ->join('product_option_groups', 'product_option_groups.id', '=', 'product_group_options.product_option_group_id')
            ->where('product_id', $id)
            ->get()
            // ->groupBy('product_option_groups.name')
            ->pluck('product_option_group_id','product_option_id')

            ->toArray();

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



            $variations = $variation_model
            ->join('product_variation_options', 'product_variation_options.variation_id', '=', 'variations.id')
            ->where('variations.product_id', $id)
            ->get()
            ->groupBy('id')
            ->toArray();

            $variations_count = $variation_model->where('variations.product_id', $id)->count();
            

            if(!empty($variations)){
                $variations_options_grouped = $productGroupOption_pivot
                ->distinct()
                ->select(
                    'product_options.name as option_name', 
                    'product_option_groups.name as group_name', 
                    'product_group_options.product_id', 
                    'product_group_options.product_option_id', 
                    'product_group_options.product_option_group_id'
                )
                ->join('product_option_groups', 'product_option_groups.id','=','product_group_options.product_option_group_id')
                ->join('product_options', 'product_options.id', '=', 'product_group_options.product_option_id')
                ->join('product_variation_options', 'product_options.id', '=', 'product_variation_options.product_option_id')

                ->where('product_group_options.product_id', $id)

                ->get()->groupBy('product_option_group_id')->toArray();
            }

            return view('admin-panel::shop.'.$this->module.'.edit', [ 
                'resource' => $translate, 
                'module' => $this->module, 
                'options' => ['slug', 'ckeditor', 'languages', 'categories', 'thumbnail'],
                'additional_options' => [
                    // [
                    //     'type' => 'editor',
                    //     'label' => 'Short Description',
                    //     'name' => 'short_description'
                    // ]
                ],
                // 'relations' => $relations,
                'languages' => $this->languages, 
                'parent_lang_id' => $parent_lang_id,
                'order' => isset($translate) ? $translate->order : $this->CRUD->getMaxOrderNumber(),
                'categories' => $categories,
                // 'brands' => $brands,
                'option_groups' => $option_groups,
                'selected_groups' => $selected_groups ?? array(),
                'selectd_group_options' => !empty($selectd_group_options) ? array_keys($selectd_group_options) : array(),
                // 'attached_relations' => $attached_relations 

                'options_grouped' => isset($variations_options_grouped) ? $variations_options_grouped : null,
                'options_count' => $variations_count,
                'variations' => $variations
            ]);

        }
    }

    public function categories()
    {
        $categories  = Category::where('type', $this->module)
        ->where('lang', $this->def_lang->code)
        ->orderBy('order', 'DESC')
        ->get()
        ->groupBy('parent_id');
        
        $languages = $this->languages;
        $type  = $this->module;

        return view('admin-panel::category.index',  compact('categories', 'type', 'languages'));
    }

    private function get_with_relations($id, $type = null){
        
        $data =  $this->product->where('id', $id)
        ->with([
            'gallery' => function( $q ){
                $q->select(['id', 'url', 'alt'])->orderBy('sort', 'ASC');
            },
            'metas' => function($q){
                $q->select('key','value', 'product_id')->get()->toArray();
            } 
        ])
        ->first();

        return $data;
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @return Response
    */
    public function updateProduct( $model, $inputs )
    {
        if(isset($inputs['category_id'])){
            $model->categories()->sync($inputs['category_id']);
        } else {
            $model->categories()->sync([]);
        }
        
        $inputs['promortion_type'] = isset($inputs['promortion_type']) && !empty($inputs['promortion_type']) ? $inputs['promortion_type'] : null;

        return $model->update($inputs);

    }
}

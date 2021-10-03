<?php

namespace Codeman\Admin\Http\Controllers\Shop\Admin;

use Illuminate\Http\Request;
use Codeman\Admin\Models\Shop\Product;
use Codeman\Admin\Models\Shop\ProductOption;
use Codeman\Admin\Models\Shop\ProductOptionGroup;
// use App\Models\ProductImages;
// use App\Models\Brand;

use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Category;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Services\CRUDService;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class ProductOptionsController extends Controller
{
    protected $languages;
    protected $def_lang;
    protected $module;

    public function __construct(ProductOptionGroup $productOptionGroup, ProductOption $productOption)
    {
        $this->CRUD = new CRUDService($productOptionGroup); //passing $brand variable as a model parameter
        $this->modelGroup = $productOptionGroup;
        $this->model = $productOption;
        $this->languages = Language::orderBy('order')->pluck('name','code')->toArray();
        $this->def_lang = Language::orderBy('order')->first(); //need to change this for bring code from languages array
        $this->module = 'product-options';

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $resources = $this->modelGroup
        ->with(['productOptions' => function($q){
            return $q->count('id');
        }])
        ->orderBy('order', 'DESC')->where([
            'lang' => $this->def_lang->code,
        ])->paginate(10);
        
        return view('admin-panel::shop.'.$this->module.'.index', [
            'resources' => $resources, 
            'module' => $this->module, 
            'dates' => $this->getDatesOfResources($this->modelGroup), 
            'languages' => $this->languages
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // if(!auth()->user()->can('create-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }

        // $categories = Category::where([
        //     'lang'=> $this->def_lang->code,
        //     'type'=> $this->module
        // ])->get()->groupBy('parent_id');

        return view('admin-panel::shop.'.$this->module.'.create_edit', [
            'order' => $this->getModelMaxOrderNumber($this->modelGroup),
            'module' => $this->module,
            'options' => ['languages'],
            'relations' => null,
           	'group_types' => [
            	'select' => 'Dropdown',
            	'radio' => 'Radio Button',
            	'colorpicker'=> 'Color Picker',
            	'image' => 'Image'
            ],
            'languages' => $this->languages,
            // 'categories'=> $categories,
        ]);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->has('lang')){
            $request['lang'] = $this->def_lang->code;
        }

        if($resource =  $this->createResource($this->modelGroup, $request->all())){
        	if ($request->has('option')) {
        		foreach ($request->get('option') as $key => $value) {
        			$resource->productOptions()->create($value);
        		}
        	}
            return redirect()->route($this->module.'.edit', $resource->id)->with('success',\Str::singular(ucwords($this->module))." Created Successfully.");
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
    public function edit($id)
    {
        // if(!auth()->user()->can('edit-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }

        // $model = $this->modelGroup;
        // if(null == $options =json_decode($model->options)){
        //     $options = [];
        // }

        // if(null == $relation_ids = json_decode($model->relations)){
        //     $slugs = [];
        // }else{
        //     $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
        // }

        // $resourcemetas = $decoded_resourcemetas;
        $resource = $this->get_resource_with_relations($this->modelGroup, $id);

        // $resource->setAttribute('meta', $resourcemetas);
        // $categories = Category::where([
        //     'lang' => $resource->lang,
        //     'type'=>$this->module
        // ])->get()->groupBy('parent_id');

        return view('admin-panel::shop.'.$this->module.'.create_edit', [ 
            'resource' => $resource, 
            'module' => $this->module, 
            'options' => ['languages'],
            'relations' => null,
            'group_types' => [
            	'select' => 'Dropdown',
            	'radio' => 'Radio Button',
            	'colorpicker'=> 'Color Picker',
            	'image' => 'Image'
            ],
            'group_options' => $resource->productOptions,
            // 'relations' => $relations,
            'languages' => $this->languages, 
            'order' => isset($resource) ? $resource->order : $this->getModelMaxOrderNumber($this->modelGroup),
            // 'categories' => $categories,
            // 'attached_relations' => $attached_relations 
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
    	// dd($request->all());
        if (strpos($request->created_at, '/') !== false) {
            $request['created_at'] = Carbon::createFromFormat('d/m/Y', $request->created_at);
        }
        
        // if(!auth()->user()->can('edit-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }
        $resource = $this->modelGroup->find($id);
        if($this->updateResource($resource, $request->all())){

            if ($request->has('option')) {
            	// $resource->productOptions()->delete();
            	foreach ($request->get('option') as $key => $value) {
            		$resource->productOptions()->updateOrCreate(['id' => $value['id']], $value);
            	}
            }else{
                $resource->productOptions()->delete();
            }

            return redirect()->route($this->module.'.edit', $id)->with('success', \Str::singular(ucwords($this->module)) . " Successfully Updated.");
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

        $resource = $this->modelGroup->find($id);
        $productOptions = $resource->productOptions()->delete();
        
        // dd($productOptions);
        
        if($resource){
            $resource->delete();
            return redirect()->back()->with('success', \Str::singular(ucwords($this->module)).' Successfully Deleted.');
        }
        return redirect()->back();
    }


    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function deleteOption($id)
    {
        // if(!auth()->user()->can('delete-'.$this->module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }

        $resource = $this->model->find($id);
        // $productOptions = $resource->productOptions()->delete();
        
        // dd($productOptions);
        
        if($resource){
            $resource->delete();
            // return redirect()->back()->with('success', \Str::singular(ucwords($this->module)).' Successfully Deleted.');
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => false]);
        // return redirect()->back();
    }


    public function translate( $id, $lang )
    {
        // if(!auth()->user()->can('update-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }
        $translate = $this->createOrEditResourceTranslation($this->modelGroup, $this->module, $id, $lang );

        if(is_array($translate) && isset($translate['status']) && $translate['status'] == 'redirect'){
            return redirect($translate['route']);
        }

        if (isset($translate) && $translate->parent_lang_id != null) {
            $parent_lang_id = null;
        } else {
            $parent_lang_id = $translate->id;
        }
        
        // $model = Module::where('title', $module)->first();

        // if (isset($translate['status']) && $translate['status'] == 'redirect') {
        //     return redirect($translate['route']);
        // }


        // if(null == $options =json_decode($model->options)){
        //     $options = [];
        // }
        // if(null == $relation_ids = json_decode($model->relations)){
        //     $slugs = [];
        // }else{
        //     $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
        // }



        // $resourcemetas = $this->CRUD->getPageMetas($id);
        // $decoded_resourcemetas = [];
        // foreach($resourcemetas as $key => $value) {
        //     if(isJson($value)){
        //         $decoded_resourcemetas[$key] = json_decode($value, true);
        //     } else {
        //         $decoded_resourcemetas[$key] = $value;
        //     }
        // }

        // $add_opts = json_decode($model->additional_options);

        // $additional_options = [];
        // foreach($add_opts as $key =>$val){
        //     $arr =[];
        //     parse_str($val, $arr);
        //     $additional_options[$key] = $arr;
        // }

        // $resourcemetas = $decoded_resourcemetas;
        // $resource = $this->CRUD->get_with_relations($id);

        // $attached_relations = array_column($resource->relations->toArray(), 'id');
        // //foreach($attached_relations as $key =>)
        // if(null == $relations = Resource::select('id', 'title', 'type')->where('lang', $resource->lang)->whereIn('type', $slugs)->get()->groupBy('type')->toArray()){
        //     $relations = [];
        //     //$slugs = is_array($slugs)? $slugs : $slugs->toArray();
        //     //foreach($slugs as $key=>$val){
        //     //$relations[$val] = [];
        //     //}
        // }

        // $translate->setAttribute('meta', $resourcemetas);
        // $categories = Category::where(['lang'=>$translate->lang, 'type'=>$module])->get()->groupBy('parent_id');
        // $translated_categories = [];
        // foreach($resource->categories->pluck('id')->toArray() as $key => $category){
        //     $trans_cat = Category::where('parent_lang_id' , $category)->where('lang', $lang)->first();
        //     if($trans_cat){
        //         $trans_cat_id = $trans_cat->id;
        //     } else{
        //         $trans_cat_id = null;
        //     }
        //     if(!$trans_cat_id){
        //         $trans_cat_parent = Category::find($category)->parent_lang_id;
        //         $trans_cat = Category::where('parent_lang_id' , $trans_cat_parent)->where('lang', $lang)->first();
        //         if($trans_cat){
        //             $trans_cat_id =  $trans_cat->id;
        //         }
        //     }
        //     if($trans_cat_id){
        //         $translated_categories[] = $trans_cat_id;
        //     }
        // }
        
        if ($translate) {
            return view('admin-panel::shop.'.$this->module.'.create_edit', [
                'resource' => $translate, 
                'module' => $this->module, 
                'options' => ['languages'],
	            'relations' => null,
	            'group_types' => [
	            	'select' => 'Dropdown',
	            	'radio' => 'Radio Button',
	            	'colorpicker'=> 'Color Picker',
	            	'image' => 'Image'
	            ],
	            'group_options' => $translate->productOptions,
                'parent_lang_id' => $parent_lang_id,
                'languages' => $this->languages, 
                'order' => isset($translate) ? $translate->order : $this->CRUD->getMaxOrderNumber(),
            ]);
        }
    }

    public function getOptionsOfGroup($id, $single_choice = false)
    {
    	$group = $this->modelGroup->find($id);
    	if($group){
    		$options =  $this->model->where('product_option_group_id', $id)
			    		->orderBy('order', 'ASC')
			    		->pluck('name','id')
			    		->toArray();
    		$html = view('admin-panel::shop.products.parts.attributes.item', 
						[
							'group' => $group,
							'options' => $options,
                            'single_choice' => request()->has('single_choice') ? 1 : 0
						]
					)->render();
    		return response()->json(['status' => 'success', 'html' => $html]);
    	}
    	return response()->json(['status' => false]);
    }

    private function get_resource_with_relations($model, $id, $type = null){
        if($type){
            $data =  $model->where('id', $id)->with(['relations' =>  function ($query) use ($type) {
                $query->where('resourceable_type', $type);
            }
            ])->get();
        } else {
            $data =  $model->where('id', $id)->first();
        }

        return $data;
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @return Response
    */
    private function updateResource($resource, $inputs )
    {
        
        // if(isset($inputs['category_id'])){
        //     $model->categories()->sync($inputs['category_id']);
        // } else {
        //     $model->categories()->sync([]);
        // }
        return $resource->update($inputs);

    }

    private function createResource($model, $inputs)
    {
    	return $model->create($inputs);
    }
}

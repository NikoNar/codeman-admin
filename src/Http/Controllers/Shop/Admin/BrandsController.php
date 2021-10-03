<?php

namespace Codeman\Admin\Http\Controllers\Shop\Admin;

use Illuminate\Http\Request;
use Codeman\Admin\Models\Shop\Brand;

use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Category;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Services\CRUDService;
use Illuminate\Support\Carbon;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;


class BrandsController extends Controller
{
    protected $languages;
    protected $def_lang;
    protected $module;

    public function __construct(Brand $brand)
    {
        $this->middleware('admin');
        $this->CRUD = new CRUDService($brand); //passing $brand variable as a model parameter
        $this->model = $brand;
        $this->languages = Language::orderBy('order')->pluck('name','code')->toArray();
        $this->def_lang = Language::orderBy('order')->first();
        $this->module = 'brands';
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $resources = $this->model
        ->orderBy('order', 'DESC')->where([
            'lang' => $this->def_lang->code,
        ])->paginate(10);
        
        return view('admin-panel::shop.'.$this->module.'.index', [
            // 'resources' => $this->CRUD->getAll($this->module), 
            'resources' => $resources, 
            'module' => $this->module, 
            'dates' => $this->getDatesOfResources($this->model), 
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

        return view('admin-panel::shop.'.$this->module.'.create_edit', [
            'order' => $this->CRUD->getMaxOrderNumber(),
            'module' => $this->module,
            'options' => ['slug', 'ckeditor', 'languages', 'thumbnail'],
            'relations' => null,
            'additional_options' => [
                // [
                //     'type' => 'editor',
                //     'label' => 'Short Description',
                //     'name' => 'short_description',
                //     'location' => 'default',
                // ],
                [
                    'type' => 'image',
                    'label' => 'Logo',
                    'name' => 'logo',
                    'location' => 'righ-sidebar'
                ]
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
        $request['first_letter'] = $request['title'][0];

        if($resource =  $this->CRUD->store($request->all())){
            if($request->has('meta'))
            {
                $this->CRUD->createUpdateMeta($resource->id, $request->get('meta'));
            }
            if($request->relations){
                $relations = (array) json_decode($request->relations, true);
                $resource->relations()->sync($relations);
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
        $model = $this->model;
        if(null == $options =json_decode($model->options)){
            $options = [];
        }

        $resource = $this->get_resource_with_relations($id);

        return view('admin-panel::shop.'.$this->module.'.create_edit', [ 
            'resource' => $resource, 
            'module' => $this->module, 
            'options' => ['slug', 'ckeditor', 'languages', 'thumbnail'],
            'additional_options' => [
                // [
                //     'type' => 'editor',
                //     'label' => 'Short Description',
                //     'name' => 'short_description',
                //     'location' => 'default',
                // ],
                [
                    'type' => 'image',
                    'label' => 'Logo',
                    'name' => 'logo',
                    'location' => 'righ-sidebar'
                ]
            ],
            'languages' => $this->languages, 
            'order' => isset($resource) ? $resource->order : $this->CRUD->getMaxOrderNumber(),
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
        if (strpos($request->created_at, '/') !== false) {
            $request['created_at'] = Carbon::createFromFormat('d/m/Y', $request->created_at);
        }
        $request['first_letter'] = $request['title'][0];

        if($resource =  $this->updateResource($id, $request->all())) {
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

        $resource = $this->model->find($id);
        // $resource->categories()->detach();
        
        if($resource){
            $resource->delete();
            $this->model->where('parent_lang_id', $id)->delete();
            return redirect()->back()->with('success', \Str::singular(ucwords($this->module)).' Successfully Deleted.');
        }
        return redirect()->back();
    }


    public function translate( $id, $lang )
    {
        // if(!auth()->user()->can('update-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
        //     abort(403);
        // }
        $translate = $this->createOrEditResourceTranslation($this->model, $this->module, $id, $lang );

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
                'options' => ['slug', 'ckeditor', 'languages', 'thumbnail'],
                'additional_options' => [
                    // [
                    //     'type' => 'editor',
                    //     'label' => 'Short Description',
                    //     'name' => 'short_description',
                    //     'location' => 'default',
                    // ],
                    [
                        'type' => 'image',
                        'label' => 'Logo',
                        'name' => 'logo',
                        'location' => 'righ-sidebar'
                    ]
                ],
                'parent_lang_id' => $parent_lang_id,
                'languages' => $this->languages, 
                'order' => isset($translate) ? $translate->order : $this->CRUD->getMaxOrderNumber(),
            ]);
        }
    }

    public function categories($module)
    {
        $default_lang = Language::orderBy('order')->first();
        $categories  = Category::where('type', $this->module)->where('lang', $default_lang->code)->orderBy('order', 'DESC')->get()->groupBy('parent_id');
        $languages = Language::orderBy('order')->pluck('name','id')->toArray();
        $type  = $module;
        return view('admin-panel::category.index',  compact('categories', 'type', 'languages'));
    }

    private function get_resource_with_relations($id, $type = null){
        if($type){
            $data =  $this->model->where('id', $id)->with(['relations' =>  function ($query) use ($type) {
                $query->where('resourceable_type', $type);
            }
            ])->get();
        } else {
            $data =  $this->model->where('id', $id)->first();
        }

        return $data;
    }

    /**
    * Update the specified resource in storage.
    *
    * @param  int  $id
    * @return Response
    */
    public function updateResource( $id, $inputs )
    {
        $model = $this->model->find($id);
        return $model->update($inputs);

    }
}

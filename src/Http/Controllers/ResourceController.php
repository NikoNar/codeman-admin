<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Models\Category;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Models\Module;
use Illuminate\Http\Request;
use Codeman\Admin\Http\Requests\ResourceRequest;
use Codeman\Admin\Services\CRUDService;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Resource;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Route;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Codeman\Admin\Models\User;

class ResourceController extends Controller
{

    protected $model;
    protected $languages;
    protected $def_lang;
    protected $module;
    /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct( Resource $model)
    {
        // $this->settings = $settings;
        // $this->middleware('admin');
        $this->CRUD = new CRUDService($model);
        $this->model = $model;
        $this->languages = Language::orderBy('order')->pluck('name','id')->toArray();
        $this->def_lang = Language::orderBy('order')->first();
        $this->module = Route::current()->parameter('resource');
        $this->check_resource($this->module);


    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    private  function check_resource($resource){

        if (!Module::where('slug', $resource)->first()){
            abort(404);
        }
        return true;
    }


    public function index($module)
    {

        $user = User::where('id', 1)->first();
        $user->assignRole('SuperAdmin');

//        dd($user->hasRole('SuperAdmin'));

//        $a = Resource::where('id',1)->first();
//        $a->relations()->attach([25 => ['resourceable_type'=>'foo blya']]);
        return view('admin-panel::resource.index', ['resources' => $this->CRUD->getAll($module), 'module' => $module, 'dates' => $this->getDatesOfResources($this->model), 'languages' => $this->languages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($module)
    {
        if(!auth()->user()->can('create-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $model = Module::where('title', $module)->first();
        if(null == $options =json_decode($model->options)){
            $options = [];
        }

        if(null == $relation_ids = json_decode($model->relations)){
            $slugs = [];
        }else{
            $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
        }
//        $relations = Resource::select('id', 'title', 'type')->whereIn('type', $slugs)->get()->groupBy('type')->toArray();
        if(null == $relations = Resource::select('id', 'title', 'type')->whereIn('type', $slugs)->get()->groupBy('type')->toArray()){
            $relations = [];
        }


//        $types = Resource::groupBy('type')->pluck('type', 'id');
        $categories = Category::where(['language_id'=> $this->def_lang->id, 'type'=>$module])->get();
        $add_opts = json_decode($model->additional_options)? :array();
        $additional_options = [];
        foreach($add_opts as $key =>$val){
            $arr =[];
            parse_str($val, $arr);
            $additional_options[$key] = $arr;
        }
        return view('admin-panel::resource.create_edit', [
                'order' => $this->CRUD->getMaxOrderNumber(),
                'module' =>$module,
                'options' => $options,
                'relations' => $relations,
                'additional_options' => $additional_options,
                'languages' => $this->languages,
                'categories'=> $categories,
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResourceRequest $request,$module)
    {
        if(!auth()->user()->can('create-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }

//        dd($request->all(), $module);
//        dd($this->def_lang);
        if(!$request->has('language_id')){
            $request['language_id'] = $this->def_lang->id;
        }
        if($resource =  $this->CRUD->store($request->all())){
            if($request->has('meta'))
            {
                $this->CRUD->createUpdateMeta($resource->id, $request->get('meta'));
            }
            if($request->relations){
                $relations = (array) json_decode($request->relations, true);
                $resource->relations()->sync($relations);
            }
            return redirect()->route('resources.edit', [$module, $resource->id])->with('success', Str::singular(ucwords($module))." Created Successfully.");
        }


    }

    /**
     * Display the specified resource.
     *
     * @param  \App\resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function show(resource $resource)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($module, $id)
    {
        if(!auth()->user()->can('edit-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $model = Module::where('title', $module)->first();
        if(null == $options =json_decode($model->options)){
            $options = [];
        }

        if(null == $relation_ids = json_decode($model->relations)){
            $slugs = [];
        }else{
            $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
        }


        $resourcemetas = $this->CRUD->getPageMetas($id);
        $decoded_resourcemetas = [];
        foreach($resourcemetas as $key => $value) {
            if(isJson($value)){
                $decoded_resourcemetas[$key] = json_decode($value, true);
            } else {
                $decoded_resourcemetas[$key] = $value;
            }
        }

        $add_opts = json_decode($model->additional_options)? : array();
        $additional_options = [];
        foreach($add_opts as $key =>$val){
            $arr =[];
            parse_str($val, $arr);
            $additional_options[$key] = $arr;
        }

        $resourcemetas = $decoded_resourcemetas;
        $resource = $this->CRUD->get_with_relations($id);
        $attached_relations = array_column($resource->relations->toArray(), 'id');
//        dd($attached_relations);
//        foreach($attached_relations as $key =>)
        if(null == $relations = Resource::select('id', 'title', 'type')->where('language_id', $resource->language_id)->whereIn('type', $slugs)->get()->groupBy('type')->toArray()){
            $relations = [];
//            $slugs = is_array($slugs)? $slugs : $slugs->toArray();
//            foreach($slugs as $key=>$val){
//                $relations[$val] = [];
//            }

        }

        $resource->setAttribute('meta', $resourcemetas);
        $categories = Category::where(['language_id'=>$resource->language_id, 'type'=>$module])->get();
        return view('admin-panel::resource.create_edit', [ 'resource' => $resource, 'module' => $module, 'options' => $options, 'additional_options' => $additional_options, 'relations' => $relations,'languages' => $this->languages, 'order' => $this->CRUD->getMaxOrderNumber(),'categories' => $categories, 'attached_relations' => $attached_relations ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\resource  $resource
     * @return \Illuminate\Http\Response
     */
    public function update(ResourceRequest $request, $module,  $id)
    {
//        dd($request->all());
        if(!auth()->user()->can('edit-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }

        if($resource =  $this->CRUD->update($id, $request->all())) {
            if ($request->has('meta')) {
                $this->CRUD->createUpdateMeta($id, $request->get('meta'));
            }else{
                $this->CRUD->deleteMetaIfExists($id);
            }
            if($request->relations){
                $relations = (array) json_decode($request->relations, true);
                $this->CRUD->getById($id)->relations()->sync($relations);
            }
            return redirect()->route('resources.edit', [$module, $id])->with('success', Str::singular(ucwords($module)) . " Successfully Updated.");
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($module, $id)
    {
        if(!auth()->user()->can('delete-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        if($this->CRUD->destroy($id)){
            return redirect()->back()->with('success', Str::singular(ucwords($module)).' Successfully Deleted.');
        }
    }


    public function translate($module, $id, $lang )
    {
        if(!auth()->user()->can('update-'.$module) && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $translate = $this->CRUD->createOrEditResourceTranslation($module, $id, $lang );
        $model = Module::where('title', $module)->first();

        if (isset($translate['status']) && $translate['status'] == 'redirect') {
            return redirect($translate['route']);
        }

        if (isset($translate) && $translate->parent_lang_id != null) {
            $parent_lang_id = null;
        } else {
            $parent_lang_id = $translate->id;
        }

        if(null == $options =json_decode($model->options)){
            $options = [];
        }
        if(null == $relation_ids = json_decode($model->relations)){
            $slugs = [];
        }else{
            $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
        }



        $resourcemetas = $this->CRUD->getPageMetas($id);
        $decoded_resourcemetas = [];
        foreach($resourcemetas as $key => $value) {
            if(isJson($value)){
                $decoded_resourcemetas[$key] = json_decode($value, true);
            } else {
                $decoded_resourcemetas[$key] = $value;
            }
        }

        $add_opts = json_decode($model->additional_options);

        $additional_options = [];
        foreach($add_opts as $key =>$val){
            $arr =[];
            parse_str($val, $arr);
            $additional_options[$key] = $arr;
        }

        $resourcemetas = $decoded_resourcemetas;
        $resource = $this->CRUD->get_with_relations($id);
        $attached_relations = array_column($resource->relations->toArray(), 'id');
//        foreach($attached_relations as $key =>)
        if(null == $relations = Resource::select('id', 'title', 'type')->where('language_id', $resource->language_id)->whereIn('type', $slugs)->get()->groupBy('type')->toArray()){
            $relations = [];
//            $slugs = is_array($slugs)? $slugs : $slugs->toArray();
//            foreach($slugs as $key=>$val){
//                $relations[$val] = [];
//            }

        }

        $translate->setAttribute('meta', $resourcemetas);
        $categories = Category::where(['language_id'=>$translate->language_id, 'type'=>$module])->get();

        if ($translate) {
            return view('admin-panel::resource.create_edit', [
                'resource' => $translate,
                'parent_lang_id' => $parent_lang_id,
                'order' => $this->CRUD->getMaxOrderNumber(),
                'languages' => $this->languages,
                'module' => $module,
                'options' => $options,
                'relations' => $relations,
                'additional_options' => $additional_options,
                'categories' => $categories,
                'attached_relations' => $attached_relations
            ]);


        }
    }

    public function categories($module)
    {
        $default_lang = Language::orderBy('order')->first();
        $def_land_id  = $default_lang->id;
        $categories  = Category::where('type', $this->module)->where('language_id', $def_land_id)->orderBy('order', 'DESC')->get();
        $languages = Language::orderBy('order')->pluck('name','id')->toArray();
        $type  = $module;
        return view('admin-panel::category.index',  compact('categories', 'type', 'languages'));
    }

}

<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Models\Module;
use Codeman\Admin\Models\Resource;
use Codeman\Admin\Models\User;
use Illuminate\Http\Request;
use Codeman\Admin\Http\Requests\PageRequest;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Services\CRUDService;
use Codeman\Admin\Interfaces\PageInterface;
use Codeman\Admin\Models\Page;
use Codeman\Admin\Models\Language;
use Illuminate\Support\Facades\Response;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;


// use Settings;

class PagesController extends Controller
{
    protected $model;
    protected $languages;
    /**
     * Run constructor
     *
     * @return Response
     */
    public function __construct(Page $model)
    {
        // $this->settings = $settings;
//    	 $this->middleware('auth:admin');
        $this->CRUD = new CRUDService($model);
        $this->model = $model;
        $this->languages = Language::orderBy('order')->pluck('name','id')->toArray();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {

        return view('admin-panel::page.index', ['pages' => $this->CRUD->getAll() , 'dates' => $this->getDatesOfResources($this->model), 'languages' => $this->languages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($lang = null, PageInterface $pageInterface)
    {
        if(!auth()->user()->can('create-page') && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }

        $template = null;
        $languages = Language::orderBy('order')->pluck('name','id')->toArray();
        $templates = Module::where('module_type', 'template')->pluck('title', 'id')->toArray();

        if(request()->has('template')){
            $template = request()->get('template');
            $model = Module::where('id', $template)->first();
            $add_opts = json_decode($model->additional_options)? : array();
            $additional_options = [];
            foreach($add_opts as $key =>$val){
                $arr =[];
                parse_str($val, $arr);
                $additional_options[$key] = $arr;
            }
        }

        if($template){
            if(null == $relation_ids = json_decode($model->relations)){
                $slugs = [];
                $attachments = null;
            }else{
                $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
                $relations = Resource::select('type', 'id', 'title')->whereIn('type', $slugs)->get();
                $attachments = $relations->groupBy('type');
            }

            return view('admin-panel::page.create_edit', [
                'template' 	=> $additional_options,
                'templates' 	=> $templates,
                'attachments' => $attachments,
                'parents' 	=> $pageInterface->getAllPagesTitlesArray(),
                'order' 	=> $pageInterface->getMaxOrderNumber(),
                'languages' => $languages,
                'language_id' => $lang
            ]);

        }else{
            return view('admin-panel::page.create_edit', [
                'parents' => $pageInterface->getAllPagesTitlesArray(),
                'order' => $pageInterface->getMaxOrderNumber(),
                'languages' => $languages,
                'language_id' => $lang,
                'templates' 	=> $templates,
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PageRequest $request, PageInterface $pageInterface )
    {
        // $this->authorize('create', $this->model);
//        dd($request->all());
        if(!auth()->user()->can('create-page') && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }

        $inputs = $pageInterface->getMaxOrderNumber($request->all());
//        dd('store');

        if(null != $page = $pageInterface->store($inputs)){
            if($request->has('meta'))
            {
                $pageInterface->createUpdateMeta($page->id, $request->get('meta'));
            }

            // return Response::json([
            //     'error' => false,
            //     'success'=> 'Page Successfully Created.',
            //     'code'  => 200,
            //     'page_id' =>$page->id
            // ], 200);
            return redirect()->route('page-edit', $page->id)->with('success', 'Page Successfully Created.');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function translate($id, $lang, PageInterface $pageInterface)
    {

        if(!auth()->user()->can('edit-page') && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }

        $template = null;
        $templates = Module::where('module_type', 'template')->pluck('title', 'id')->toArray();
        $languages = Language::orderBy('order')->pluck('name','id')->toArray();
        if(request()->has('template')){
            $template = request()->get('template');
        }
        $translate = $pageInterface->createOrEditTranslation($id, $lang);

        if(isset($translate['status']) && $translate['status'] == 'redirect'){
            return redirect($translate['route']);
        }

        if(isset($translate) && $translate->parent_lang_id != null) {
            $parent_lang_id = null;
        }else {
            $parent_lang_id = $translate->id;
        }
        // dd($parent_lang_id);
        if($translate)
        {

            if(!$template){
                $template = $translate->template;
            }
            $pagemetas = $pageInterface->getPageMetas($translate->id);
            $decoded_pagemetas = [];


            foreach($pagemetas as $key => $value) {
                if(isJson($value)){
                    $decoded_pagemetas[$key] = json_decode($value, true);
                } else {
                    $decoded_pagemetas[$key] = $value;
                }
            }
            $pagemetas = $decoded_pagemetas;
//            dd($pagemetas);
            $translate->setAttribute('meta', $pagemetas);


            $model = Module::where('id', $template)->first();

            if($model){
                $add_opts = json_decode($model->additional_options);
                $additional_options = [];
                foreach($add_opts as $key =>$val){
                    $arr =[];
                    parse_str($val, $arr);
                    $additional_options[$key] = $arr;
                }
            } else {
                $additional_options = [];
            }

            if(!$model || null == $relation_ids = json_decode($model->relations)){
                $slugs = [];
                $attachments = null;
                $selected_attachments = [];

            }else{
                $meta_attachments = $decoded_pagemetas['attachments'];
                $selected_attachments = [];
                foreach($meta_attachments as $key => $val){
                    if($val != "all" || $val != ""){
                        $selected_attachments = array_merge($selected_attachments, explode(',', $val));
                    }
                }
                $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
                $relations = Resource::select('type', 'id', 'title')->whereIn('type', $slugs)->where('language_id', $translate->language_id)->get();
                $attachments = $relations->groupBy('type')->toArray();
            }


            return view('admin-panel::page.create_edit', [
                'page' => $translate,
                'parents' => $pageInterface->getAllPagesTitlesArray($translate->language_id),
                'template' 	=> $additional_options,
                'templates' 	=> $templates,
                'attachments' => $attachments,
                'selected_attachments' => $selected_attachments,
                'parent_lang_id' => $parent_lang_id,
                'languages' => $languages
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id, PageInterface $pageInterface)
    {

        if(!auth()->user()->can('edit-page') && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $template = null;
        $templates = Module::where('module_type', 'template')->pluck('title', 'id')->toArray();
        $page = $pageInterface->getById($id);
        $languages = Language::orderBy('order')->pluck('name','id')->toArray();

        if(request()->has('template')) {
            $template = request()->get('template');
            $model = Module::where('id', $template)->first();
        } elseif($page->template != null){
            $template = $page->template;
            $model = Module::where('id', $template)->first();
        } else {
            $model = null;
        }
        if($model){
            $add_opts = json_decode($model->additional_options)? : array();
            $additional_options = [];

            foreach($add_opts as $key =>$val){
                $arr =[];
                parse_str($val, $arr);
                $additional_options[$key] = $arr;
            }
        } else {
            $additional_options = [];
        }

        $pagemetas = $pageInterface->getPageMetas($id);
        $decoded_pagemetas = [];
        foreach($pagemetas as $key => $value) {
            if(isJson($value)){
                $decoded_pagemetas[$key] = json_decode($value, true);
            } else {
                $decoded_pagemetas[$key] = $value;
            }
        }
        $page->setAttribute('meta', $decoded_pagemetas);

        if(!$model || null == $relation_ids = json_decode($model->relations)){
            $slugs = [];
            $attachments = null;
            $selected_attachments = [];

        }else{
            if(key_exists('attachments', $decoded_pagemetas)){
                $meta_attachments = $decoded_pagemetas['attachments'];
                $selected_attachments = [];
                foreach($meta_attachments as $key => $val){
                    if($val != "all" || $val != ""){
                        $selected_attachments = array_merge($selected_attachments, explode(',', $val));
                    }
                }
                $slugs = Module::whereIn('id', $relation_ids)->pluck('slug');
                $relations = Resource::select('type', 'id', 'title')->whereIn('type', $slugs)->where('language_id', $page->language_id)->get();
                $attachments = $relations->groupBy('type')->toArray();
            } else {
                $attachments = null;
                $selected_attachments = null;
            }

        }
//		$pagemetas = $decoded_pagemetas;
//		$page->setAttribute('meta', $decoded_pagemetas);
        if($template){
            return view('admin-panel::page.create_edit', [
                'template' 	=> $additional_options,
                'templates' 	=> $templates,
                'attachments' => $attachments,
                'selected_attachments' => $selected_attachments,
                'page' => $page,
                'parents' => $pageInterface->getAllPagesTitlesArray($page->language_id,$id),
                'languages' => $languages
            ]);
        }else{
            return view('admin-panel::page.create_edit', ['page' => $page, 'parents' => $pageInterface->getAllPagesTitlesArray($page->language_id,$id),'languages' => $languages,'templates' => $templates]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id, PageRequest $request, PageInterface $pageInterface)
    {
        // $this_page = $pageInterface->getById($id);
        // $this->authorize('update', $this->model);
//		 dd(request()->all());

//        dd('update');
        if(!auth()->user()->can('edit-page') && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }

        if(null != $page = $pageInterface->update($id, $request->all())){
            if($request->has('meta'))
            {
                $pageInterface->createUpdateMeta($id, $request->get('meta'));
            }else{
                $pageInterface->deleteMetaIfExists($id);
            }
            return redirect()->back()->with('success', 'Page Successfully Updated.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id, PageInterface $pageInterface)
    {
        if(!auth()->user()->can('delete-page') && !auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        if($pageInterface->destroy($id)){
            return redirect()->back()->with('success', 'Page Successfully Deleted.');
        }
    }
    public function templates(){
        $modules = Module::where('module_type', 'template')->paginate(15);
        return view('admin-panel::modules.index', compact('modules'));
    }
}
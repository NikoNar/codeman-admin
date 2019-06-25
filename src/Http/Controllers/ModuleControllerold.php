<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Http\Requests\ModuleRequest;
use Codeman\Admin\Models\Category;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Models\Module;
use Codeman\Admin\Services\CRUDService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class ModuleControllerold extends Controller
{
    protected $model;
    protected $languages;
    /**
     * Run constructor
     *
     * @return Response
     */
    public function __construct(Module $model)
    {
        // $this->settings = $settings;
        // $this->middleware('admin');
        $this->CRUD = new CRUDService($model);
        $this->model = $model;
    }

    /**
     * Display a listing of the modules.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-panel::modules.index', ['modules' => $this->CRUD->getAll() , 'dates' => $this->getDatesOfResources($this->model)]);
    }

    /**
     * Show the form for creating a new modules.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-panel::modules.create', [
            'order' => $this->CRUD->getMaxOrderNumber(),
            'categories' => Category::where('type', 'module')->get(),
            'languages' => $this->languages
        ]);
    }

    /**
     * Store a newly created modules in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuleRequest $request)
    {
        $module =  $this->CRUD->store($request->all());
        if(!empty($request->category_id)){
            foreach($request->category_id as $key=>$id){
                $module->categories()->attach($id);
            }
        }
        return redirect()->route('modules.edit', $module->id)->with('success', 'Module Created Successfully.');
    }

    /**
     * Display the specified modules.
     *
     * @param  \App\Module  $modules
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified modules.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        // dd(Category::select('title_en as title', 'id', 'type', 'slug')->where('type', 'module')->get());
        return view('admin-panel::modules.edit', [
            'module' => $this->CRUD->getById($id),
            'categories' => Category::select('title_en as title', 'id', 'type', 'slug')->where('type', 'module')->get(),
            'languages' => $this->languages
        ]);
    }

    /**
     * Update the specified module in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(ModuleRequest $request,  $id)
    {

        $this->CRUD->update($id, $request->all());
        Module::find($id)->categories()->sync($request->category_id);
        return redirect()->route('modules.edit', $id)->with('success', 'Module Successfully Updated.');
    }

    /**
     * Remove the specified modules from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->CRUD->destroy($id)){
            return redirect()->back()->with('success', 'Module Successfully Deleted.');
        }
    }

    public function categories()
    {
        $categories  = Category::where('type', 'module')->get();
        $type  = 'module';
        return view('admin-panel::category.index',  compact('categories', 'type'));
    }



    public function translate($id, $lang)
    {
        $translate = $this->CRUD->createOrEditTranslation($id, $lang);
        if(isset($translate['status']) && $translate['status'] == 'redirect'){
            return redirect($translate['route']);
        }

        if(isset($translate) && $translate->parent_lang_id != null) {
            $parent_lang_id = null;
        }else {
            $parent_lang_id = $translate->id;
        }

        if($translate)
        {
            return view('admin-panel::modules.edit', [
                'module' => $translate,
                'parent_lang_id' => $parent_lang_id,
                'categories' => Category::select('title_en as title', 'id', 'type', 'slug')->where('type', 'module')->get(),
                'order' => $this->CRUD->getMaxOrderNumber(),
                'languages' => $this->languages,
            ]);
        }
    }
}

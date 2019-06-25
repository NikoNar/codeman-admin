<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Models\Language;
use Illuminate\Http\Request;
use Codeman\Admin\Http\Requests\ServiceRequest;
use Codeman\Admin\Services\CRUDService;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Service;
use Illuminate\Support\Facades\Response;

class ServiceController extends Controller
{

    protected $model;
    protected $languages;
    /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(Service $model)
    {
        // $this->settings = $settings;
        // $this->middleware('admin');
        $this->CRUD = new CRUDService($model);
        $this->model = $model;
        $this->languages = Language::orderBy('order')->pluck('name','id')->toArray();
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-panel::service.index', ['services' => $this->CRUD->getAll() , 'dates' => $this->getDatesOfResources($this->model), 'languages' => $this->languages]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-panel::service.create', [
                'order' => $this->CRUD->getMaxOrderNumber(),
                'languages' => $this->languages
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ServiceRequest $request)
    {
        $service =  $this->CRUD->store($request->all());     
        return redirect()->route('service-edit', $service->id)->with('success', 'Service Created Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\service  $service
     * @return \Illuminate\Http\Response
     */
    public function show(Service $service)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return view('admin-panel::service.edit', [ 'service' => $this->CRUD->getById($id),  'languages' => $this->languages,]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\service  $service
     * @return \Illuminate\Http\Response
     */
    public function update(ServiceRequest $request, $id)
    {
        $this->CRUD->update($id, $request->all());
        return redirect()->route('service-edit', $id)->with('success', 'Service Successfully Updated.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->CRUD->destroy($id)){
            return redirect()->back()->with('success', 'Service Successfully Deleted.');
        }
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
            return view('admin-panel::service.edit', [
                'service' => $translate,
                'parent_lang_id' => $parent_lang_id,
                'order' => $this->CRUD->getMaxOrderNumber(),
                'languages' => $this->languages,
            ]);
        }
    }
}
    
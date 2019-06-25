<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Codeman\Admin\Http\Requests\ProgramRequest;
use Codeman\Admin\Services\CRUDService;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Program;
use Codeman\Admin\Models\Category;
use Illuminate\Support\Facades\Response;

class ProgramController extends Controller
{

    protected $model;
    /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(Program $model)
    {
        // $this->settings = $settings;
        // $this->middleware('admin');
        $this->CRUD = new CRUDService($model);
        $this->model = $model;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin-panel::program.index', ['programs' => $this->CRUD->getAll() , 'dates' => $this->getDatesOfResources($this->model), 'languages' => true]);
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-panel::program.create', [
                'order' => $this->CRUD->getMaxOrderNumber(),
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProgramRequest $request)
    {
        $program =  $this->CRUD->store($request->all());     
        return redirect()->route('program-edit', $program->id)->with('success', 'Program Inserted Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
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
         return view('admin-panel::program.edit', [ 'program' => $this->CRUD->getById($id)]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Program  $program
     * @return \Illuminate\Http\Response
     */
    public function update(ProgramRequest $request, $id)
    {
         
        $this->CRUD->update($id, $request->all());
        return redirect()->route('program-edit', $id)->with('success', 'Program Successfully Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->CRUD->destroy($id)){
            return redirect()->back()->with('success', 'Program Successfully Deleted.');
        }
    }



    public function translate($id)
    {
       
        $translate = $this->CRUD->createOrEditTranslation($id);
        if(isset($translate) && $translate->parent_lang_id != null) {
            $parent_lang_id = null;
        }else {
            $parent_lang_id = $translate->id;
        }
        // dd($parent_lang_id);
        if($translate)
        {
            return view('admin-panel::program.edit', [
                'program' => $translate,
                'parent_lang_id' => $parent_lang_id,
                'categories' => Category::where('type', 'program')->get(),
                'order' => $this->CRUD->getMaxOrderNumber(),
            ]);
        }
    }
}

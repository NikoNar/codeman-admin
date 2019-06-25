<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Codeman\Admin\Http\Requests\ResourceRequest;
use Codeman\Admin\Services\CRUDService;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Resource;
use Codeman\Admin\Models\Category;
use Illuminate\Support\Facades\Response;

class PortfolioController extends Controller
{

    protected $model;
    /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(Resource $model)
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
        return view('admin-panel::resource.index', ['resources' => $this->CRUD->getAll() , 'dates' => $this->getDatesOfResources($this->model), 'languages' => true]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-panel::resource.create', [
                'order' => $this->CRUD->getMaxOrderNumber(),
                'categories' => Category::where('type', 'resource')->get()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ResourceRequest $request)
    {
        $this->authorize('create', $this->model);
        // dd($request->all()); 
        $resource =  $this->CRUD->store($request->all());
        if(!empty($request->category_id)){
                foreach($request->category_id as $key=>$id){
                    $resource->categories()->attach($id); 
                }
            }
     
        return redirect()->route('resource-edit', $resource->id)->with('success', 'Resource Inserted Successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Protfolio  $protfolio
     * @return \Illuminate\Http\Response
     */
    public function show(Protfolio $protfolio)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Protfolio  $protfolio
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        return view('admin-panel::resource.edit', [ 
            'resource' => $this->CRUD->getById($id),
            'categories' => Category::where('type', 'resource')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Protfolio  $protfolio
     * @return \Illuminate\Http\Response
     */
    public function update(ResourceRequest $request,  $id)
    {
        
        $this->CRUD->update($id, $request->all());
        Resource::find($id)->categories()->sync($request->category_id);
        return redirect()->route('resource-edit', $id)->with('success', 'Resource Successfully Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->CRUD->destroy($id)){
            return redirect()->back()->with('success', 'Resource Successfully Deleted.');
        }
    }

    public function categories()
    {
        $categories  = Category::where('type', 'resource')->get();
        $type  = 'resource';
        return view('admin-panel::category.index',  compact('categories', 'type'));
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
            return view('admin-panel::resource.edit', [
                'resource' => $translate,
                'parent_lang_id' => $parent_lang_id,
                'categories' => Category::where('type', 'resource')->get(),
                'order' => $this->CRUD->getMaxOrderNumber(),
            ]);
        }
    }
}

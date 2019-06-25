<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Codeman\Admin\Http\Requests\roleRequest;
use Codeman\Admin\Services\CRUDService;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\Role;
use Codeman\Admin\Models\Category;
use Illuminate\Support\Facades\Response;

class roleController extends Controller
{

    protected $model;
    /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(Role $model)
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
         return view('admin-panel::roles.index', ['roles' => Role::paginate(10), 'dates' => $this->getDatesOfResources($this->model), 'languages' => false]);
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin-panel::roles.create', [
                'order' => $this->CRUD->getMaxOrderNumber(),
                'categories' => Category::where('type', 'role')->get()
            ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(roleRequest $request)
    {
        // dd($request->all()); 
        $role =  Role::create($request->all());
        if(!empty($request->category_id)){
                foreach($request->category_id as $key=>$id){
                    $role->categories()->attach($id); 
                }
            }
     
        return redirect()->route('roles.edit', $role->id)->with('success', 'role Inserted Successfully.');
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
        
        return view('admin-panel::roles.edit', [ 
            'role' => $this->CRUD->getById($id),
            'categories' => Category::where('type', 'role')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Protfolio  $protfolio
     * @return \Illuminate\Http\Response
     */
    public function update(roleRequest $request,  $id)
    {
        
        $this->CRUD->update($id, $request->all());
        // role::find($id)->categories()->sync($request->category_id);
        return redirect()->route('roles.edit', $id)->with('success', 'role Successfully Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->CRUD->destroy($id)){
            return redirect()->back()->with('success', 'role Successfully Deleted.');
        }
    }

    public function categories()
    {
        $categories  = Category::where('type', 'role')->get();
        $type  = 'role';
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
            return view('admin-panel::role.edit', [
                'role' => $translate,
                'parent_lang_id' => $parent_lang_id,
                'categories' => Category::where('type', 'role')->get(),
                'order' => $this->CRUD->getMaxOrderNumber(),
            ]);
        }
    }
}

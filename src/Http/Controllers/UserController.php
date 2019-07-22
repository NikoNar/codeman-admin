<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Models\Module;
use Illuminate\Http\Request;
use Codeman\Admin\Http\Requests\UserRequest;
use Codeman\Admin\Services\CRUDService;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Models\User;
use Codeman\Admin\Models\Category;
use Illuminate\Support\Facades\Response;
use Avatar;
use Illuminate\Support\Str;


class UserController extends Controller
{

    protected $model;
    /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(User $model)
    {
        // $this->settings = $settings;
        $this->middleware('admin');
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
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
         return view('admin-panel::user.index', ['users' => $this->model->paginate(20) , 'dates' => $this->getDatesOfResources($this->model)]);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $modules = Module::pluck('slug')->toArray();
        return view('admin-panel::user.create_edit', compact('modules'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $profile_pic_filename = Str::random(32).'.png';
        $profile_pic = Avatar::create($request->name)->save(public_path().'/images/users/'.$profile_pic_filename);
        $user = new User;
        $user->name = $request->name;
        $user->profile_pic = $profile_pic_filename;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->save();
        if($request->role){
            $user->assignRole($request->role);
        }
        if($request->permissions){
            $permissions = json_decode($request->permissions);
            foreach($permissions as $permission){
                $user->givePermissionTo($permission);
            }
        }

        return redirect()->route('user.index')->with('success', 'User Created Successfully.');
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
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $modules = Module::pluck('slug')->toArray();
        return view('admin-panel::user.create_edit', [
            'user' => $this->CRUD->getById($id),
            'modules' => $modules,
            // 'categories' => Category::where('type', 'User')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Protfolio  $protfolio
     * @return \Illuminate\Http\Response
     */
    public function update(UserRequest $request,  $id)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $this->CRUD->update($id, $request->all());
        $user = User::where('id', $id)->first();

        if($request->role){
            $user->syncRoles($request->role);
        }
        if($request->permissions){
            $permissions = json_decode($request->permissions);
            $user->syncPermissions($permissions);
        }


        return redirect()->route('user.edit', $id)->with('success', 'User Successfully Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        if($this->CRUD->destroy($id)){
            return redirect()->back()->with('success', 'User Successfully Deleted.');
        }
    }

    public function categories()
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $categories  = Category::where('type', 'User')->get();
        $type  = 'User';
        return view('admin-panel::category.index',  compact('categories', 'type'));
    }

    public function translate($id)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $translate = $this->CRUD->createOrEditTranslation($id);
        if(isset($translate) && $translate->parent_lang_id != null) {
            $parent_lang_id = null;
        }else {
            $parent_lang_id = $translate->id;
        }
        // dd($parent_lang_id);
        if($translate)
        {
            return view('admin-panel::user.create_edit', [
                'user' => $translate,
                'parent_lang_id' => $parent_lang_id,
                'categories' => Category::where('type', 'User')->get(),
                'order' => $this->CRUD->getMaxOrderNumber(),
            ]);
        }
    }
}

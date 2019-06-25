<?php

namespace Codeman\Admin\Http\Controllers;

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

         return view('admin-panel::user.index', ['users' => $this->model->paginate(20) , 'dates' => $this->getDatesOfResources($this->model)]);

    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('admin-panel::user.create');

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UserRequest $request)
    {
        // dd($request->all());
        $profile_pic_filename = Str::random(32).'.png';
        $profile_pic = Avatar::create($request->name)->save(public_path().'/images/users/'.$profile_pic_filename);
        $user = new User;
        $user->name = $request->name;
        $user->profile_pic = $profile_pic_filename;
        $user->email = $request->email;
        $user->password = \Hash::make($request->password);
        $user->save();
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
        
        return view('admin-panel::user.edit', [ 
            'user' => $this->CRUD->getById($id),
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
        
        $this->CRUD->update($id, $request->all());
        User::find($id)->categories()->sync($request->category_id);
        return redirect()->route('user-edit', $id)->with('success', 'User Successfully Updated.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if($this->CRUD->destroy($id)){
            return redirect()->back()->with('success', 'User Successfully Deleted.');
        }
    }

    public function categories()
    {
        $categories  = Category::where('type', 'User')->get();
        $type  = 'User';
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
            return view('admin-panel::user.edit', [
                'user' => $translate,
                'parent_lang_id' => $parent_lang_id,
                'categories' => Category::where('type', 'User')->get(),
                'order' => $this->CRUD->getMaxOrderNumber(),
            ]);
        }
    }
}

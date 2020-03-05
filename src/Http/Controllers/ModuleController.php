<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Http\Requests\ModuleRequest;
use Codeman\Admin\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;

class ModuleController extends Controller
{
    public function __construct()
    {
        $this->middleware('role:SuperAdmin');

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
        $modules = Module::where('module_type', 'module')->orderBy('order','DESC')->paginate(15);
        return view('admin-panel::modules.index', compact('modules'));
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
        $relations = Module::where('module_type', 'module')->pluck('title', 'id');
        return view('admin-panel::modules.create_edit' , compact('relations'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ModuleRequest $request)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
//        dd($request->all());
        if(isset($request->options)){
            $request['options'] = json_encode($request->options);
        }

        if(isset($request->relations)){
            $request['relations'] = json_encode($request->relations);
        }
//        dd($request->all(0));
//        $request['options'] = json_encode($request->options);
        $request['slug'] = Str::slug($request['title']);
        if(Permission::where(['name' => 'create-'.$request['slug']])->first() === null){
            Permission::firstOrCreate(['name' => 'create-'.$request['slug']]);
        };

        if(Permission::where(['name' => 'edit-'.$request['slug']])->first() === null){
            Permission::firstOrCreate(['name' => 'edit-'.$request['slug']]);
        };

        if(Permission::where(['name' => 'delete-'.$request['slug']])->first() === null){
            Permission::firstOrCreate(['name' => 'delete-'.$request['slug']]);
        };
        
        $module = Module::create($request->all());
        return redirect()->route('modules.edit', $module->id)->with('success', 'Module Created Successfully.');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function show(Module $module)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function edit(Module $module)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $add_opts = json_decode($module->additional_options)? : array();

        $additional_options = [];
        $relations = Module::where('module_type', 'module')->pluck('title', 'id');
        foreach($add_opts as $key =>$val){
            $arr =[];
            parse_str($val, $arr);
            $additional_options[$key] = $arr;
        }
        return view('admin-panel::modules.create_edit', compact('module', 'additional_options', 'relations'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Module $module)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
//        dd($request->all());
        $relations = Module::pluck('title', 'id');
        if(isset($request->options)){
            $request['options'] = json_encode($request->options);
        }else{
            $request['options'] = null;
        }

        if(isset($request->relations)){
            $request['relations'] = json_encode($request->relations);
        }else{
            $request['relations'] = null;
        }
        $module->update($request->all());
        $add_opts = json_decode($module->additional_options);
        $additional_options = [];
        foreach($add_opts as $key =>$val){
            $arr =[];
            parse_str($val, $arr);
            $additional_options[$key] = $arr;
        }
        return view('admin-panel::modules.create_edit', compact('module', 'additional_options', 'relations'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Module  $module
     * @return \Illuminate\Http\Response
     */
    public function destroy(Module $module)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $module->delete();
        /// detach
        return redirect()->back()->with('success', 'Module Successfully Deleted.');
    }

    public function delete(Module $module, $id)
    {
        if(!auth()->user()->hasAnyRole('SuperAdmin|Admin')){
            abort(403);
        }
        $module = $module->where('id', $id)->first();
        Permission::where('name', 'create-'.$module->slug)->delete();
        Permission::where('name', 'edit-'.$module->slug)->delete();
        Permission::where('name', 'delete-'.$module->slug)->delete();
        $module->delete();
        /// detach
        return redirect()->back()->with('success', 'Module Successfully Deleted.');
    }
}
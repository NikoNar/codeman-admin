<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Http\Requests\ModuleRequest;
use Codeman\Admin\Models\Module;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ModuleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $modules = Module::where('module_type', 'module')->paginate(15);
        return view('admin-panel::modules.index', compact('modules'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $relations = Module::pluck('title', 'id');
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
        $add_opts = json_decode($module->additional_options);
        $additional_options = [];
        $relations = Module::pluck('title', 'id');
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
        $module->delete();
        /// detach
        return redirect()->back()->with('success', 'Module Successfully Deleted.');
    }

    public function delete(Module $module, $id)
    {
        $module->where('id', $id)->delete();
        /// detach
        return redirect()->back()->with('success', 'Module Successfully Deleted.');
    }
}

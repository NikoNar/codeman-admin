<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Codeman\Admin\Models\Application;

class ApplicationController extends Controller
{
    public function index(){

   		 $applications = Application::paginate(20);
   		 return view('admin-panel::application.index', compact('applications'));
    }

    public function destroy($id){
    	$app = Application::find($id);

    	if(is_dir($app->user_path)){
    		deleteDir($app->user_path);
      	}

    	$app->delete();
    	return back()->with('success', 'Application deleted succesfully');
    }
}

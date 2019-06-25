<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Codeman\Admin\Interfaces\MenuInterface;
use Codeman\Admin\Interfaces\PageInterface;
use Codeman\Admin\Http\Requests\MenuRequest;


class MenusController extends Controller
{
    	/**
           * Run constructor
           *
           * @return Response
           */
        public function __construct()
        {
        	// $this->middleware('admin');
        }

        /**
           * Display a listing of the resource.
           *
           * @return Response
           */
        public function index(MenuInterface $menuInterface, PageInterface $pageInterface)
        {
            // return view('admin.menus.index', ['menus' => $menuInterface->getAll()]);
        	return view('admin-panel::menus.index');
        }

    	/**
    	* Show the form for creating a new resource.
    	*
    	* @return Response
    	*/
    	public function create(PageInterface $pageInterface)
    	{
        	return view('admin-panel::menus.create');
    	}

    	/**
    	* Store a newly created resource in storage.
    	*
    	* @return Response
    	*/
    	public function store(MenuInterface $menuInterface, MenuRequest $request)
    	{
    		if(null != $menu = $menuInterface->store($request->all())){
    			return redirect()->route('menus-show', $menu->id)->with('success', 'Menu Successfully Created.');
    		}
    	}

    	/**
    	* Display the specified resource.
    	*
    	* @param  int  $id
    	* @return Response
    	*/
    	public function show($id, MenuInterface $menuInterface, PageInterface $pageInterface)
    	{
        	return view('admin-panel::menus.show', ['pages' => $pageInterface->getAllPagesForMenu()]);
    	}

		/**
		* Display the specified resource.
		*
		* @param  int  $id
		* @return Response
		*/
		public function translate($id, MenuInterface $menuInterface, PageInterface $pageInterface)
		{
	    	return view('admin-panel::menus.show', ['pages' => $pageInterface->getAllPagesForMenu()]);
		}

    	

    	/**
    	* Show the form for editing the specified resource.
    	*
    	* @param  int  $id
    	* @return Response
    	*/
    	public function edit($id)
    	{
        	
    	}

    	/**
    	* Update the specified resource in storage.
    	*
    	* @param  int  $id
    	* @return Response
    	*/
    	public function update($id)
    	{
    		
    	}

    	/**
    	* Remove the specified resource from storage.
    	*
    	* @param  int  $id
    	* @return Response
    	*/
    	public function destroy($id)
    	{
    		
    	}
}

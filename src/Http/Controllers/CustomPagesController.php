<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Admin\Controller;
use App\Models\Team;
use App\Models\Category;
use App\Http\Requests\Admin\TeamRequest;

class CustomPagesController extends Controller
{
   
   	/**
	  * Run constructor
	  *
	  * @return Response
	  */
	public function __construct()
	{
		$this->middleware('admin');
	}

	/**
	  * Display a listing of the resource.
	  *
	  * @return Response
	  */
	public function team(Team $model)
	{
		return view('admin.custom-page.team', ['teams' => $model->where('lang','en')->orderBy('order', 'DESC')->paginate(10)]);
	}

	public function categories()
	{
		return view('admin.category.index', ['categories' => $this->getResourceCategories('team'), 'type' => 'team']);
	}

	/**
	  * Display a form of the adding new recource.
	  *
	  * @return Response
	  */
	public function teamCreate(Team $model, Category $category)
	{
    	$categories = $category->whereType('team')->where('parent_id', '<=', 0)->get();

		return view('admin.custom-page.team-create', [
			'categories' => $categories,
			'order' => getMaxOrderNumber('Team')
		] );
	}

	/**
	  * Display a form of the adding new recource.
	  *
	  * @return Response
	  */
	public function teamStore(TeamRequest $request, Team $model)
	{
		if($request->has('category_en_new') && $request->get('category_en_new') != '' ){
			$request['category_en'] = $request->get('category_en_new');
		}
		if($request->has('category_arm_new') && $request->get('category_arm_new') != '' ){
			$request['category_arm'] = $request->get('category_arm_new');
		}
		$model->create($request->all());
		return redirect()->action("Admin\CustomPagesController@team")->with('success', 'Team Member Successfully Created.');
	}

	/**
	  * Display a form of the adding new recource.
	  *
	  * @return Response
	  */
	public function teamEdit($id, Team $model, Category $category)
	{
    	$categories = $category->whereType('team')->where('parent_id', '<=', 0)->get();

		return view('admin.custom-page.team-create', [
			'member' => $model->find($id),
			'categories' => $categories
		]);
	}

	/**
	  * Display a form of the adding new recource.
	  *
	  * @return Response
	  */
	public function teamUpdate($id, TeamRequest $request, Team $model)
	{

		if($request->has('category_en_new') && $request->get('category_en_new') != '' ){
			$request['category_en'] = $request->get('category_en_new');
		}
		if($request->has('category_arm_new') && $request->get('category_arm_new') != '' ){
			$request['category_arm'] = $request->get('category_arm_new');
		}
		$model->find($id)->update($request->all());
		return redirect()->action("Admin\CustomPagesController@team")->with('success', 'Team Member Successfully Updated.');
	}


	/**
	  * Display a form of the adding new recource.
	  *
	  * @return Response
	  */
	public function teamTranslate($id, Team $model, Category $category)
	{
    	$categories = $category->whereType('team')->where('parent_id', '<=', 0)->get();
		
		$translate = $this->createOrEditTranslation($id, $model);
		if($translate)
		{
    		return view('admin.custom-page.team-create', [
    			'member' => $translate,
    			'categories' => $categories,
    			// 'order' => getMaxOrderNumber('Team')
    		]);
		}
		return view('admin.custom-page.team-create', [
			'parent_lang_id' => $id,
			"name" => $model->find($id)->title,
			'categories' => $categories,
			'order' => getMaxOrderNumber('Team')
		]);
	}


	private function createOrEditTranslation( $id, $model )
	{
		if(null != $parent_lang = $model->where('parent_lang_id', $id)->first()){
			return $parent_lang;
		}
		return null;
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function teamDestroy($id, Team $model)
	{
		if($model->find($id)->delete()){
			return redirect()->back()->with('success', 'Team Member Successfully Deleted.');
		}
	}

   	/**
   	* Show the form for creating a new resource.
   	*
   	* @return Response
   	*/
   	public function contactUs()
   	{
		return view('admin.custom-page.contact-us');
   	}

   	/**
   	* Store a newly created resource in storage.
   	*
   	* @return Response
   	*/
   	public function store( PageRequest $request, PageInterface $pageInterface )
   	{
   		if(null != $page = $pageInterface->store($request->all())){
   			return redirect()->action('Admin\PagesController@edit', $page->id)->with('success', 'Page Successfully Created.');
   		}
   	}

   	/**
   	* Display the specified resource.
   	*
   	* @param  int  $id
   	* @return Response
   	*/
   	public function show($id)
   	{
   		//
   	}

   	/**
   	* Show the form for editing the specified resource.
   	*
   	* @param  int  $id
   	* @return Response
   	*/
   	public function edit($id, PageInterface $pageInterface)
   	{
       	return view('admin.page.edit', ['page' => $pageInterface->getById($id), 'parents' => $pageInterface->getAllPagesTitlesArray($id)]);
   	}

   	/**
   	* Update the specified resource in storage.
   	*
   	* @param  int  $id
   	* @return Response
   	*/
   	public function update($id, PageRequest $request, PageInterface $pageInterface)
   	{
   		if(null != $page = $pageInterface->update($id, $request->all())){
   			return redirect()->action('Admin\PagesController@edit', $id)->with('success', 'Page Successfully Updated.');
   		}
   	}

   

}

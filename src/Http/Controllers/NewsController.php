<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;
use Codeman\Admin\Http\Controllers\Controller;
use Codeman\Admin\Interfaces\NewsInterface;
use Codeman\Admin\Http\Requests\NewsRequest;
use Codeman\Admin\Models\News;
use Illuminate\Support\Facades\Response;
use Codeman\Admin\Models\Category;

class NewsController extends Controller
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

    public function index(NewsInterface $newsInterface, News $model)
    {
    	return view('admin-panel::news.index', [
    		'news' => $newsInterface->getAll(),
    		'years' => $this->getDatesOfResources($model),
    		'languages' => true]);
    }

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create(NewsInterface $newsInterface, Category $category)
	{
    	$categories = $category->whereType('news')->where('parent_id', '<=', 0)->get();
    	return view('admin-panel::news.create', [
    		'order' => getMaxOrderNumber('News'),
    		'categories' => $categories
    	]);
	}

	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store( NewsRequest $request, NewsInterface $newsInterface )
	{
		// dd($request->all());
		if(null != $news = $newsInterface->store($request->all())){
			// return redirect()->route('news-edit', $news->id)->with('success', 'News Successfully Created.');
			if(!empty($request->category_id)){
				foreach($request->category_id as $key=>$id){
					$news->categories()->attach($id); 
				}
			}

			return Response::json([
                'error' => false,
                'success'=> 'Page Successfully Created.',
                'code'  => 200,
                'news_id' =>$news->id
            ], 200);
		}
	}

	/**
	* Display the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function show(News $model, $slug)
	{
		
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function edit($id, NewsInterface $newsInterface, Category $category)
	{
		$categories = $category->whereType('news')->where('parent_id', '<=', 0)->get();
    	return view('admin-panel::news.edit', 
    		[
    			'news' => $newsInterface->getSingleNews($id),
    			'categories' => $categories
    		]);
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function translate($id, NewsInterface $newsInterface)
	{
		$translate = $newsInterface->createOrEditTranslation($id);
		if($translate)
		{
    		return view('admin-panel::news.edit', ['news' => $translate]);
		}
    	return view('admin-panel::news.create', [
    		'parent_lang_id' => $id,
    		'order' => getMaxOrderNumber('News')
    		
    	]);
	}
	

	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update($id, NewsRequest $request, NewsInterface $newsInterface)
	{
		if(null != $news = $newsInterface->update($id, $request->all())){
			
			News::find($id)->categories()->sync($request->category_id); 
		
			return redirect()->route('news-edit', $id)->with('success', 'News Successfully Updated.');
		}
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy($id, NewsInterface $newsInterface)
	{
		if($newsInterface->destroy($id)){
			return redirect()->back()->with('success', 'Page Successfully Deleted.');
		}
	}

	public function categories()
    {
      return view('admin-panel::category.index', ['categories' => $this->getResourceCategories('news'), 'type' => 'news']);
    }
}

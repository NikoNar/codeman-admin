<?php

namespace Codeman\Admin\Http\Controllers;

use Codeman\Admin\Models\Language;
use Codeman\Admin\Models\Module;
use Illuminate\Http\Request;
use Codeman\Admin\Http\Controllers\Controller;

use Codeman\Admin\Models\Category;
use Illuminate\Support\Str;


class CategoriesController extends Controller
{

    protected $languages;
    protected $model;
	/**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(Category $category)
    {
        $this->model = $category;
    	// $this->middleware('admin');
        $this->languages = Language::orderBy('order')->pluck('name','id')->toArray();
    }

    /**
       * Display a listing of the resource.
       *
       * @return Response
       */
    public function index(Category $model)
    {
    	$categories = $model->where('parent_id', '=', 0)->orderBy('order', 'DESC')->get();
    	// dd($categories);
    	// $allCategories = $model->pluck('title_en','id')->all();
    	return view('admin-panel::category.index', ['categories' => $categories, 'languages' => true]);
    }

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create( Request $request,  Category $model, $type)
	{
        if($request->ajax()) {
            $categories = $model->where('type', $type)->where('parent_id', '<=', 0)->orderBy('order', 'DESC')->get();
            $returnHTML = view('admin-panel::category.parts._category_modal', [
                'categories' => $categories,
                'module' => $type,
                'order' => getMaxOrderNumber('Category'),
                'languages' => $this->languages,
                'ajax' => true

            ])->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        }else{
            $categories = $model->where('type', $type)->where('parent_id', '<=', 0)->orderBy('order', 'DESC')->get();
            return view('admin-panel::category.create_edit', [
                'categories' => $categories,
                'type' => $type,
                'order' => getMaxOrderNumber('Category'),
                'languages' => $this->languages

            ]);
        }
	}

	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store( Request $request, Category $category )
	{
//	    dd($request->all());
        $request['slug'] = getUniqueSlug($category, $request['title']);
        if($request->ajax()) {
            $category = $category->create($request->except('selected'));
            if(isset($request['selected'])){
             $keys =explode(',', $request['selected']);
                array_push($keys, $category->id);
                $categories = Category::where(['type' =>$category->type, 'language_id' =>$category->language_id ])->get();
                $returnHTML = view('admin-panel::components.categories', [
                    'render' => true,
                    'categories' => $categories,
                    'selected' => $keys,
                    'module' => $category->type
                ])->render();
            }

            return  response()->json(array('success' => 'Category successfully created.', 'html' => $returnHTML));
        } else {
            $category = $category->create($request->all());
            return redirect()->route('categories.edit', [ $category->id, $request['type']])->with('success', "Category Created Successfully.");

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
	public function edit($id, $type, Category $category, Request $request)
	{
        $category = $category->find($id);
        $categories = $category->where('type', $type)
            ->where('parent_id', '<=', 0)
            ->where('language_id', $category->language_id)
            ->where('id', '!=', $id)->orderBy('order', 'DESC')->get();
        if($request->ajax()) {
            $returnHTML = view('admin-panel::category.create_edit', [
                'category' => $category,
                'categories' => $categories,
                'type' => $type,
                'order' => getMaxOrderNumber('Category'),
            ])->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        } else {
            return view('admin-panel::category.create_edit', [ 'category' => $category, 'categories' => $categories, 'languages' => $this->languages, 'type'=> $type ]);

        }
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update($id, Request $request, Category $category)
	{
		$request['slug'] = getUniqueSlug($category, $request['title_en'], $id);
		$category->find($id)->update($request->all());
		return redirect()->back()->with('success', 'Category successfully updated.');
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy($id, category $category)
	{
		if($category->find($id)->delete()){
			return redirect()->back()->with('success', 'Category Successfully Deleted.');
		}
		return redirect()->back()->with('error', 'Something whent wrong.');

	}

    public function translate($id, $lang )
    {
        $translate = $this->createOrEditCategoryTranslation( $id, $lang );
        if (isset($translate['status']) && $translate['status'] == 'redirect') {
            return redirect($translate['route']);
        }

        if (isset($translate) && $translate->parent_lang_id != null) {
            $parent_lang_id = null;
        } else {
            $parent_lang_id = $translate->id;
        }


        if ($translate) {
            $parent = $translate->parent_id;
            if($parent){
                $parentTrans = Category::where('language_id', $lang)->where('parent_lang_id', $parent)->get();
            } else {
                $parentTrans = null;
            }
            return view('admin-panel::category.create_edit', [
                'category' => $translate,
                'categories' => $parentTrans,  //parent category
                'parent_lang_id' => $parent_lang_id,
                'order' => $this->model->max('order')+1,
                'languages' => $this->languages,
                'type'=> $translate->type,
            ]);
        }

    }

    public function createOrEditCategoryTranslation($id, $lang )
    {
        $category = $this->model->where(['id' => $id, 'language_id' => $lang])->orWhere(['id' => $id])->first();
        if(!$category){
            return ['status' => 'redirect', 'route' => route('categories.create') ];
        }

        if($category->language_id != $lang && isset($category->parent_lang_id)){

            $parent_cat = $this->model->where(['id' => $category->parent_lang_id, 'language_id' => $lang])->first();

            if($parent_cat){
//                dd('s');
                return ['status' => 'redirect', 'route' => route('categories.edit', [$parent_cat->id, $category->type])];
            }else if(null != $trans_cat = $this->model->where(['parent_lang_id' => $category->parent_lang_id, 'language_id' => $lang])->first()){
                return ['status' => 'redirect', 'route' => route('categories.edit', [$trans_cat->id, $category->type])];
            }else{
                $trans_cat = $this->model->where('id', $category->parent_lang_id)->first();
                $trans_cat['language_id'] = $lang;
                return $trans_cat;
            }


        } else if($category->language_id != $lang && !isset($category->parent_lang_id)) {
            $parent_cat = $this->model->where(['parent_lang_id' => $category->id, 'language_id' => $lang])->first();
            if($parent_cat ){
                return ['status' => 'redirect', 'route' => route('categories.edit', [$parent_cat->id, $parent_cat->type])];
            }
            $category['language_id'] = $lang;
            return $category;
        }else{
            $category['language_id'] = $lang;
            return $category;
        }

        $category = $this->model->find($id);
        $category['language_id'] = $lang;

        return $category;


    }

    public function categories_by_lang($type, $lang, $parent = null){
        $categories = Category::where(['type' =>$type, 'language_id' => $lang ])->get();
        $data = [
            'render' => true,
            'categories' => $categories,
            'selected' => '',
            'module' => $type
        ];
        if ($parent != null){
            $view = 'admin-panel::layouts.parts._parent_category';
        } else {
            $view = 'admin-panel::components.categories';
        }
        $returnHTML = view($view, $data)->render();
        return  response()->json(array('success' => 'Category successfully created.', 'html' => $returnHTML));

    }


}

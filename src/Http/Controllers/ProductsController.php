<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;

use Codeman\Admin\Http\Controllers\Controller;

use Codeman\Admin\Interfaces\ProductInterface;

use Codeman\Admin\Http\Requests\ProductRequest;

use Codeman\Admin\Models\Product;

use Codeman\Admin\Models\Category;

class ProductsController extends Controller
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
    public function index(ProductInterface $productInterface, product $model)
    {
    	return view('admin-panel::product.index', [
    		'products' => $productInterface->getAll(),
    		'years' => $this->getDatesOfResources($model),
    		'languages' => true]);
    }

	/**
	* Show the form for creating a new resource.
	*
	* @return Response
	*/
	public function create(ProductInterface $productInterface, Category $category)
	{	
    	$categories = $category->whereType('product')->where('parent_id', '<=', 0)->get();
    	return view('admin-panel::product.create', [
    		'order' => getMaxOrderNumber('product'),
    		'categories' => $categories

    	]);
	}

	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store( productRequest $request, ProductInterface $productInterface )
	{
	    $colorsData = $request->colors;
	    $colorsArr = [];
	    for($i=0; $i<count($colorsData['color']); $i++){
            array_push($colorsArr,[]);
        }
	    foreach($colorsData as $data ){
            foreach($data as $key=>$value){
	            array_push($colorsArr[$key], $value);
            }
        }
        $colorsArr = json_encode($colorsArr);
        $request->request->add(['variations' => $colorsArr]);

        if(null != $product = $productInterface->store($request->all())){
			return redirect()->route('product-edit', $product->id)->with('success', 'Product Successfully Created.');
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
	public function edit($id, ProductInterface $productInterface,  Category $category)
	{
        $categories = $category->where('parent_id', '=', 0)->where('type', 'product')->orderBy('order', 'DESC')->get();
        $product = $productInterface->getSingleproduct($id);
        $colors =json_decode($product->variations);
//        dd(count($colors[0]));
        return view('admin-panel::product.edit', [
    		'product' => $product,
    		'categories' => $categories,
    		'colors' => $colors,
    	]);
	}

	/**
	* Show the form for editing the specified resource.
	*
	* @param  int  $id
	* @return Response
	*/
	public function translate($id, ProductInterface $productInterface)
	{
		$translate = $productInterface->createOrEditTranslation($id);
		if($translate)
		{
    		return view('admin-panel::product.edit', ['product' => $translate]);
		}
    	return view('admin-panel::product.create', [
    		'parent_lang_id' => $id,
    		'order' => getMaxOrderNumber('product')
    		
    	]);
	}
	

	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update($id, productRequest $request, ProductInterface $productInterface)
	{
	    $colorsData = $request->colors;
	    $colorsArr = [];
	    if(!empty($colorsData)){
		    for($i = 0; $i < count($colorsData['color']); $i++){
	            array_push($colorsArr,[]);
	        }
		    foreach($colorsData as $data ){
	            foreach($data as $key=>$value){
		            array_push($colorsArr[$key], $value);
	            }
	        }
        	$colorsArr = json_encode($colorsArr);
	    }else{
	    	$colorsArr = null;
	    }
        $request->request->add(['variations' => $colorsArr]);

		if(null != $product = $productInterface->update($id, $request->all())){
			return redirect()->route('product-edit', $id)->with('success', 'Product Successfully Updated.');
		}
	}

	public function categories()
	{
		return view('admin-panel::category.index', ['categories' => $this->getResourceCategories('product'), 'type' => 'product']);
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy($id, ProductInterface $productInterface)
	{
		if($productInterface->destroy($id)){
			return redirect()->back()->with('success', 'Product Successfully Deleted.');
		}
	}
}

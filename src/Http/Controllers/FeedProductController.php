<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Http\Request;

use Codeman\Admin\Models\FeedProduct;
use Codeman\Admin\Models\Variation;

use Codeman\Admin\Http\Controllers\Controller;

class FeedProductController extends Controller
{ 
    /**
       * Run constructor
       *
       * @return Response
       */
    public function __construct(FeedProduct $feedProduct)
    {
      $this->feedProduct =  $feedProduct;
      // $this->middleware('admin');
    }
    public function index (){

      $result = $this->feedProduct;
      if(request()->has('search') && request()->get('search') != null)
      {
        if(request()->has('search_by')){
            $result = $result->where(request()->get('search_by'), 'LIKE', '%'.request()->get('search').'%');
        }else{
            $result = $result->where('name', 'LIKE', '%'.request()->get('search').'%');
        }
      }
      if(request()->has('brand_name') && request()->get('brand_name') != null)
      {
        $result = $result->where('brand_name', request()->get('brand_name'));
      }
      $result = $result->orderBy('order', 'DESC');

      if(request()->has('per-page')){
        $result = $result->paginate((int) request()->get('per-page'));
      }else{
        $result = $result->paginate(10);
      }

        $brands = $this->getAllBrandsArray();
        return view("admin-panel::feedproduct.index", ['feedproducts' => $result, 'brands' => $brands]);
    }

    public function show($id)
    {
    	$product = $this->feedProduct->with(['variations' =>function($query){
    		$query->groupBy('color');
    	} ])->find($id);

    	return view('admin-panel::feedproduct.edit', compact('product'));
  	
    }

    public function update($id, Request $request, Variation $variation){
        $this->feedProduct->find($id)
            ->update($request->all());
        $variation->find($request->get('prod_variation_id'))->update(['images' => $request->get('gallery')]);
        return redirect()->back()->with('success', 'Product has been updated.');
    }

    public function changeStatus(Request $request)
    {
       $this->feedProduct->where('id', $request->id)
            ->update(['status' => $request->status]);

        return response()->json($request->status);

    }

    public function categories()
    {
      return view('admin-panel::category.index', ['categories' => $this->getResourceCategories('feed-product'), 'type' => 'feed-product']);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->feedProduct->where('id', $id)->delete();

        return redirect()->route('feedproduct-index');
    }


    /**
    * Select all ids and brand names of resource from storage.
    *
    * @return Array
    */
    private function getAllBrandsArray()
    {
    return $this->feedProduct->groupBy('brand_name')->pluck('brand_name','id')->toArray();
    }


}

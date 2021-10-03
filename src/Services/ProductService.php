<?php

namespace Codeman\Admin\Services;

use Codeman\Admin\Interfaces\ProductInterface;

use Codeman\Admin\Models\Product;
use Codeman\Admin\Models\BaseModel;
use Image;

class ProductService implements ProductInterface
{
	/**
	 * The object of Product class.
	 *
	 * @var Product
	 */
	protected $product;

	/**
	 * Product constructor.
	 *
	 * @param  Product 
	 */
	public function __construct( Product $product )
	{
		$this->product = $product;
	}

	/**
	* Select all resources from storage.
	*
	* @return Object
	*/
	public function getAll()
	{
		return $this->product->orderBy('order', 'DESC')->paginate(10);
	}

	/**
	* Select the specified resource from storage by id.
	*
	* @param  int  $id
	* @return Object
	*/
	public function getById( $id )
	{
		return $this->product->find($id);
	}

	/**
	* Select the specified resource from storage by id.
	*
	* @param  int  $id
	* @return Object
	*/
	public function getSingleProduct( $id )
	{
		$product = $this->product->find($id);
		if($product){
			$product_date = date('m/d/Y' ,strtotime($product->created_at));
			$product_time = date('g:i A' ,strtotime($product->created_at));
			$product->published_date = $product_date;
			$product->published_time = $product_time;
			return $product;
		}	
		return false;
	}

	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store( $inputs )
	{
		return $this->product->create($this->createInputs( $inputs ));
	}

	public function createOrEditTranslation( $id )
	{
		if(null != $parent_lang = $this->product->where('parent_lang_id', $id)->first()){
			$product_date = date('m/d/Y' ,strtotime($parent_lang->created_at));
			$product_time = date('g:i A' ,strtotime($parent_lang->created_at));
			$parent_lang->published_date = $product_date;
			$parent_lang->published_time = $product_time;
			return $parent_lang;
		}
		return null;
	}

	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update( $id, $inputs )
	{	
		$product = $this->getById($id);
		$product->categories()->detach();
		if(isset($inputs['category_id']) && !empty($inputs['category_id'])){
			$product->categories()->attach($inputs['category_id']);
		}	
		return $product->update($this->updateInputs($id, $inputs ));
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy( $id )
	{
		return $this->getById($id)->delete();
	}

	/**
	* Filtering and checking the data before store
	*
	* @param  int  $id
	* @return Response
	*/
	private function createInputs( $inputs)
	{
		$date  = $inputs['published_date'].' '.$inputs['published_time'];
		$published_full_date = date('Y-m-d H:i:s', strtotime($date));
		if($published_full_date){
			$inputs['created_at'] = $published_full_date;
		}

		$inputs['slug'] = getUniqueSlug($this->product, $inputs['title']);
		$inputs['meta-title'] =  isset($inputs['meta-title']) ? $inputs['meta-title'] : $inputs['title'];
		// $inputs['thumbnail'] = isset($inputs['thumbnail']) ? $this->uploadImage(request()->file('thumbnail')) : null;

		return $inputs;
	}

	/**
	* Filtering and checking the data before update
	*
	* @param  int  $id
	* @return Response
	*/
	private function updateInputs( $id, $inputs )
	{
		$date  = $inputs['published_date'].' '.$inputs['published_time'];
		$published_full_date = date('Y-m-d H:i:s', strtotime($date));
		if($published_full_date){
			$inputs['created_at'] = $published_full_date;
		}
		
		if($this->getById($id)->slug !=  $inputs['slug']){
			$inputs['slug'] = getUniqueSlug($this->product, $inputs['slug'], $id);
		}
		$inputs['meta-title'] =  isset($inputs['meta-title']) ? $inputs['meta-title'] : $inputs['title'];
		// if(isset($inputs['thumbnail'])){
		// 	$inputs['thumbnail'] = isset($inputs['thumbnail']) ? $this->uploadImage(request()->file('thumbnail')) : null;
		// }
		return $inputs;
	}

	private function uploadImage( $image ){
		if($image) {
			$filename  = time() . '.' . $image->getClientOriginalExtension();

			$path = public_path('images/product/' . $filename);

			// Image::make($image->getRealPath())->resize(200, 200)->save($path);
			Image::make($image->getRealPath())->save($path);
			// dd($filename);
			return $filename;
        }
	}
}
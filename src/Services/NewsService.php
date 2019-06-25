<?php

namespace Codeman\Admin\Services;

use Codeman\Admin\Interfaces\NewsInterface;

use Codeman\Admin\Models\News;
use Codeman\Admin\Models\BaseModel;
use Image;

class NewsService implements NewsInterface
{
	/**
	 * The object of News class.
	 *
	 * @var News
	 */
	protected $news;

	/**
	 * News constructor.
	 *
	 * @param  News 
	 */
	public function __construct( News $news )
	{
		$this->news = $news;
	}

	/**
	* Select all resources from storage.
	*
	* @return Object
	*/
	public function getAll()
	{
		return $this->news->orderBy('order', 'DESC')->where('lang','en')->paginate(10);
	}

	/**
	* Select the specified resource from storage by id.
	*
	* @param  int  $id
	* @return Object
	*/
	public function getById( $id )
	{
		return $this->news->find($id);
	}

	/**
	* Select the specified resource from storage by id.
	*
	* @param  int  $id
	* @return Object
	*/
	public function getSingleNews( $id )
	{
		$news = $this->news->find($id);
		if($news){
			$news_date = date('m/d/Y' ,strtotime($news->created_at));
			$news_time = date('g:i A' ,strtotime($news->created_at));
			$news->published_date = $news_date;
			$news->published_time = $news_time;
			return $news;
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

		return $this->news->create($this->createInputs( $inputs ));
	}

	public function createOrEditTranslation( $id )
	{
		if(null != $parent_lang = $this->news->where('parent_lang_id', $id)->first()){
			$news_date = date('m/d/Y' ,strtotime($parent_lang->created_at));
			$news_time = date('g:i A' ,strtotime($parent_lang->created_at));
			$parent_lang->published_date = $news_date;
			$parent_lang->published_time = $news_time;
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
		return $this->getById($id)->update($this->updateInputs($id, $inputs ));
	}

	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy( $id )
	{	
		$news = $this->getById($id);
		$news->categories()->detach();
		return $news->delete();
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
		$inputs['slug'] = getUniqueSlug($this->news, $inputs['title']);
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
			$inputs['slug'] = getUniqueSlug($this->news, $inputs['slug'], $id);
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

			$path = public_path('images/news/' . $filename);

			// Image::make($image->getRealPath())->resize(200, 200)->save($path);
			Image::make($image->getRealPath())->save($path);
			// dd($filename);
			return $filename;
        }
	}
}
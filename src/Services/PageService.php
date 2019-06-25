<?php

namespace Codeman\Admin\Services;

use Codeman\Admin\Interfaces\PageInterface;

use Codeman\Admin\Models\Page;
use Codeman\Admin\Models\Pagemeta;
use Image;

class PageService implements PageInterface
{
	/**
	 * The object of Page class.
	 *
	 * @var Page
	 */
	protected $page;

	/**
	 * Page constructor.
	 *
	 * @param  News 
	 */
	public function __construct( Page $page, Pagemeta $pagemeta )
	{
		$this->page = $page;
		$this->pagemeta = $pagemeta;
		// $this->auth = auth()->user();
	}



	/**
	* Select the specified resource from storage by id.
	*
	* @param  int  $id
	* @return Object
	*/
	public function getById( $id )
	{
		return $this->page->find($id);
	}

	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store( $inputs )
	{
		return $this->page->create($this->createInputs( $inputs ));
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
		return $this->getById($id)->delete();
	}

	/**
	* Select all ids and titles of resource from storage.
	*
	* @return Array
	*/
	public function getAllPagesTitlesArray($current_page_id = null)
	{
		if( $current_page_id ) {
			return $this->page->where('id', '!=', $current_page_id )->pluck('title', 'id')->toArray();
		}else{
			return $this->page->all()->pluck('title', 'id')->toArray();
		}
	}

	public function createOrEditTranslation( $id, $lang )
	{
//	    dd($id, $lang, 'createoredit');
//		if(null != $parent_lang = $this->page->where('parent_lang_id', $id)->first()){
//		    if($parent_lang->language_id == $lang){
//                return $parent_lang;
//            }
//			// $news_date = date('m/d/Y' ,strtotime($parent_lang->created_at));
//			// $news_time = date('g:i A' ,strtotime($parent_lang->created_at));
//			// $parent_lang->published_date = $news_date;
//			// $parent_lang->published_time = $news_time;
//		}
//		$page =  $this->page->find($id);
//		$page['language_id'] = $lang;
//		return $page;


        $page = $this->page->where(['id' => $id, 'language_id' => $lang])->orWhere(['id' => $id])->first();

        if(!$page){
            return ['status' => 'redirect', 'route' => route('page-create', $lang) ];
        }

        if($page->language_id != $lang && isset($page->parent_lang_id)){

            $parent_page = $this->page->where(['id' => $page->parent_lang_id, 'language_id' => $lang])->first();

            if($parent_page){
                return ['status' => 'redirect', 'route' => route('page-edit', $parent_page->id)];
            }else if(null != $trans_page = $this->page->where(['parent_lang_id' => $page->parent_lang_id, 'language_id' => $lang])->first()){
                return ['status' => 'redirect', 'route' => route('page-edit', $trans_page->id)];
            }else{
                $trans_page = $this->page->where('id', $page->parent_lang_id)->first();
                $trans_page['language_id'] = $lang;
                return $trans_page;
            }

            
        } else if($page->language_id != $lang && !isset($page->parent_lang_id)) {
            $parent_page = $this->page->where(['parent_lang_id' => $page->id, 'language_id' => $lang])->first();
            if($parent_page ){
                return ['status' => 'redirect', 'route' => route('page-edit', $parent_page->id)];
            }
            $page['language_id'] = $lang;
            return $page;
        }else{
            $page['language_id'] = $lang;
            return $page;
        }
	}

	/**
	* Select all ids and titles of resource from storage.
	*
	* @return Array
	*/
	public function getAllPagesForMenu($current_page_id = null)
	{
		return $this->page->all()->pluck('title', 'id')->toArray();
	}

	public function getMaxOrderNumber($inputs = null)
	{
		if($inputs){
			$inputs['order'] = $this->page->max('order') + 1;
			return $inputs;
		}else{
			return $this->page->max('order') + 1;
		}

	}

	/**
	* Filtering and checking the data before store
	*
	* @param  int  $id
	* @return Response
	*/
	private function createInputs( $inputs)
	{
		$inputs['slug'] = getUniqueSlug($this->page, $inputs['title']);
		$inputs['meta-title'] =  isset($inputs['meta-title']) ? $inputs['meta-title'] : $inputs['title'];
		// $inputs['thumbnail'] = isset($inputs['thumbnail']) ? $this->uploadImage(request()->file('thumbnail')) : null;

		return $inputs;
	}

	public function createUpdateMeta($page_id, $inputs)
	{
		$pagemetas = $this->pagemeta->where('page_id', $page_id)->get();
		if( isset($pagemetas) && !$pagemetas->isEmpty() && isset($inputs) && !empty($inputs) ) {
			$newInputs = array();
			$updateInputs = array();
			$updateInputsIds = array();
			$i = 0;
			foreach ($pagemetas as $key => $value) {
				if(	array_key_exists($value->key, $inputs)) {
					$updateInputs['value'] = is_array($inputs[$value->key]) ? json_encode($inputs[$value->key]) : $inputs[$value->key];
					$value->update($updateInputs);
					unset($inputs[$value->key]);
				}
			}
		}
		if(isset($inputs) && !empty($inputs))
		{
			$newInputs = array();
			$j = 0;
			foreach ($inputs as $key => $value) {
			
				$newInputs[$j]['page_id'] = $page_id;
				$newInputs[$j]['key'] = $key;
				$newInputs[$j]['value'] = is_array($value) ? json_encode($value) : $value;
				++$j;
			}
		}
		if(isset($newInputs) && !empty($newInputs)){
			$this->pagemeta->insert($newInputs);
		}
		return true;
	}
	public function deleteMetaIfExists($page_id)
	{
		return $this->pagemeta->where('page_id', $page_id)->delete();
	}

	public function getPageMetas($page_id)
	{
		return $this->pagemeta->where('page_id', $page_id)->select('key', 'value')->pluck('value', 'key')->toArray();
	}

	/**
	* Filtering and checking the data before update
	*
	* @param  int  $id
	* @return Response
	*/
	private function updateInputs( $id, $inputs )
	{
		if($this->getById($id)->slug !=  $inputs['slug']){
			$inputs['slug'] = getUniqueSlug($this->page, $inputs['slug'], $id);
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

			$path = public_path('images/pages/' . $filename);

			// Image::make($image->getRealPath())->resize(200, 200)->save($path);
			Image::make($image->getRealPath())->save($path);
			// dd($filename);
			return $filename;
        }
	}
}
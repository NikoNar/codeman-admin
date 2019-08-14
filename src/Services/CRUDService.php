<?php

namespace Codeman\Admin\Services;

use Codeman\Admin\Interfaces\CRUDInterface;
use Codeman\Admin\Models\BaseModel;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Models\Page;
use Codeman\Admin\Models\Resource;
use Codeman\Admin\Models\Resourcemeta;
use function foo\func;
use Illuminate\Database\Eloquent\Model;
use Image;
use Illuminate\Support\Str;

class CRUDService implements CRUDInterface
{
	/**
	 * The object of model class.
	 *
	 * @var model
	 */
	protected $model;
	protected $default_language;
	protected $resourcemeta;

	/**
	 * model constructor.
	 *
	 * @param  model 
	 */
	public function __construct($model)
	{
		$this->model = $model;
		$this->default_language = Language::orderBy('order')->first();
		$this->resourcemeta = new Resourcemeta;
	}

	/**
	* Select all resources from storage.
	*
	* @return Object
	*/
	public function getAll($module = null)
	{
	    if($module){
            return $this->model->orderBy('order', 'DESC')->where(['language_id'=>$this->default_language->id, 'type' =>$module])->paginate(10);
        } else{
            return $this->model->orderBy('order', 'DESC')->where('language_id',$this->default_language->id)->paginate(10);
        }
        // dd($this->model->orderBy('order', 'DESC')->where('lang','en')->paginate(10));
	}

	/**
	* Select the specified resource from storage by id.
	*
	* @param  int  $id
	* @return Object
	*/
	public function getById( $id )
	{
		return $this->model->find($id);
	}

	
	/**
	* Store a newly created resource in storage.
	*
	* @return Response
	*/
	public function store($inputs)
	{
//	    dd($inputs);
        $model = $this->model->create($this->createInputs($inputs));
//        dd($model);
//        dd($this->createInputs( $inputs ));
        if(isset($inputs['category_id'])){
            $model->categories()->sync($inputs['category_id']);
        }
        return $model;
    }

	public function createOrEditTranslation( $id, $lang )
	{

        $model= str_singular($this->model->getTable());
        $page = $this->model->where(['id' => $id, 'language_id' => $lang])->orWhere(['id' => $id])->first();

        if(!$page){
            return ['status' => 'redirect', 'route' => route($model.'-create', $lang) ];
        }

        if($page->language_id != $lang && isset($page->parent_lang_id)){

            $parent_page = $this->model->where(['id' => $page->parent_lang_id, 'language_id' => $lang])->first();

            if($parent_page){
                return ['status' => 'redirect', 'route' => route($model.'-edit', $parent_page->id)];
            }else if(null != $trans_page = $this->model->where(['parent_lang_id' => $page->parent_lang_id, 'language_id' => $lang])->first()){
                return ['status' => 'redirect', 'route' => route($model.'-edit', $trans_page->id)];
            }else{
                $trans_page = $this->model->where('id', $page->parent_lang_id)->first();
                $trans_page['language_id'] = $lang;
                return $trans_page;
            }


        } else if($page->language_id != $lang && !isset($page->parent_lang_id)) {
            $parent_page = $this->model->where(['parent_lang_id' => $page->id, 'language_id' => $lang])->first();
            if($parent_page ){
                return ['status' => 'redirect', 'route' => route($model.'-edit', $parent_page->id)];
            }
            $page['language_id'] = $lang;
            return $page;
        }else{
            $page['language_id'] = $lang;
            return $page;
        }

		// if(null != $parent_lang = $this->model->where('parent_lang_id', $id)->first()){
		// 	$model_date = date('m/d/Y' ,strtotime($parent_lang->created_at));
		// 	$model_time = date('g:i A' ,strtotime($parent_lang->created_at));
		// 	$parent_lang->published_date = $model_date;
		// 	$parent_lang->published_time = $model_time;
		// 	return $parent_lang;

		// }
		// return null;
		
//		if(null != $parent_lang = $this->model->where('parent_lang_id', $id)->first()){
//			// $news_date = date('m/d/Y' ,strtotime($parent_lang->created_at));
//			// $news_time = date('g:i A' ,strtotime($parent_lang->created_at));
//			// $parent_lang->published_date = $news_date;
//			// $parent_lang->published_time = $news_time;
//			return $parent_lang;
//		}
        $resourse = $this->model->find($id);
        $resourse['language_id'] = $lang;

        return $resourse;


    }



    public function createOrEditResourceTranslation($type, $id, $lang)
    {

        $model= str_singular($this->model->getTable());
        $page = $this->model->where(['id' => $id, 'language_id' => $lang])->orWhere(['id' => $id])->first();

        if(!$page){
            return ['status' => 'redirect', 'route' => route('resources.create', [$type, $lang]) ];
        }

        if($page->language_id != $lang && isset($page->parent_lang_id)){
            $parent_page = $this->model->where(['id' => $page->parent_lang_id, 'language_id' => $lang])->first();

            if($parent_page){
                return ['status' => 'redirect', 'route' => route('resources.edit', [$type,$parent_page->id])];
            }else if(null != $trans_page = $this->model->where(['parent_lang_id' => $page->parent_lang_id, 'language_id' => $lang])->first()){
                return ['status' => 'redirect', 'route' => route('resources.edit', [$type,$trans_page->id])];
            }else{
                $trans_page = $this->model->where('id', $page->parent_lang_id)->first();
                $trans_page['language_id'] = $lang;
                return $trans_page;
            }


        } else if($page->language_id != $lang && !isset($page->parent_lang_id)) {
            $parent_page = $this->model->where(['parent_lang_id' => $page->id, 'language_id' => $lang])->first();
            if($parent_page ){
                return ['status' => 'redirect', 'route' => route('resources.edit', [$type,$parent_page->id])];
            }
            $page['language_id'] = $lang;
            return $page;
        }else{
            $page['language_id'] = $lang;
            return $page;
        }

        // if(null != $parent_lang = $this->model->where('parent_lang_id', $id)->first()){
        // 	$model_date = date('m/d/Y' ,strtotime($parent_lang->created_at));
        // 	$model_time = date('g:i A' ,strtotime($parent_lang->created_at));
        // 	$parent_lang->published_date = $model_date;
        // 	$parent_lang->published_time = $model_time;
        // 	return $parent_lang;

        // }
        // return null;

//		if(null != $parent_lang = $this->model->where('parent_lang_id', $id)->first()){
//			// $news_date = date('m/d/Y' ,strtotime($parent_lang->created_at));
//			// $news_time = date('g:i A' ,strtotime($parent_lang->created_at));
//			// $parent_lang->published_date = $news_date;
//			// $parent_lang->published_time = $news_time;
//			return $parent_lang;
//		}
        $resourse = $this->model->find($id);
        $resourse['language_id'] = $lang;

        return $resourse;


    }

	/**
	* Update the specified resource in storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function update( $id, $inputs )
	{
        $model = $this->getById($id);
//        dd( $model->categories()->exists());
        if(isset($inputs['category_id'])){
	        $model->categories()->sync($inputs['category_id']);
        } else {
            $model->categories()->sync([]);
        }
		return $model->update($inputs);

	}



	public function getMaxOrderNumber($inputs = null)
	{
		if($inputs){
			$inputs['order'] = $this->model->max('order') + 1;
			return $inputs;
		}else{
			return $this->model->max('order') + 1;
		}

	}


	/**
	* Remove the specified resource from storage.
	*
	* @param  int  $id
	* @return Response
	*/
	public function destroy( $id )
	{	
		$model = $this->getById($id);
		 $model->categories()->detach();
		return $model->delete();
	}

	/**
	* Filtering and checking the data before store
	*
	* @param  int  $id
	* @return Response
	*/
	private function createInputs($inputs)
	{
        if(in_array('slug', $inputs)){
            $inputs['slug'] = getUniqueSlug($this->model, $inputs['slug']);
        } else {
            $inputs['slug'] = getUniqueSlug($this->model, $inputs['title']);
        }

        $inputs['meta-title'] =  isset($inputs['meta-title']) ? $inputs['meta-title'] : $inputs['title'];
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
			$inputs['slug'] = getUniqueSlug($this->model, $inputs['slug'], $id);
		}
		$inputs['meta-title'] =  isset($inputs['meta-title']) ? $inputs['meta-title'] : $inputs['title'];
		// if(isset($inputs['thumbnail'])){
		// 	$inputs['thumbnail'] = isset($inputs['thumbnail']) ? $this->uploadImage(request()->file('thumbnail')) : null;
		// }
		return $inputs;
	}

    public function createUpdateMeta($resource_id, $inputs)
    {
        $resourcemetas = $this->resourcemeta->where('resource_id', $resource_id)->get();
        if( isset($resourcemetas) && !$resourcemetas->isEmpty() && isset($inputs) && !empty($inputs) ) {
            $newInputs = array();
            $updateInputs = array();
            $updateInputsIds = array();
            $i = 0;
            foreach ($resourcemetas as $key => $value) {
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

                $newInputs[$j]['resource_id'] = $resource_id;
                $newInputs[$j]['key'] = $key;
                $newInputs[$j]['value'] = is_array($value) ? json_encode($value) : $value;
                ++$j;
            }
        }
        if(isset($newInputs) && !empty($newInputs)){
            $this->resourcemeta->insert($newInputs);
        }
        return true;
    }


    public function getPageMetas($resource_id)
    {
        return $this->resourcemeta->where('resource_id', $resource_id)->select('key', 'value')->pluck('value', 'key')->toArray();
    }

	private function uploadImage( $image ){
		if($image) {
			$filename  = time() . '.' . $image->getClientOriginalExtension();

			$path = public_path('images/model/' . $filename);

			// Image::make($image->getRealPath())->resize(200, 200)->save($path);
			Image::make($image->getRealPath())->save($path);
			// dd($filename);
			return $filename;
        }
	}

    public function deleteMetaIfExists($id)
    {
        return $this->resourcemeta->where('id', $id)->delete();
    }




    public function get_with_relations($id, $type = null){
	    if($type){
            $data =  Resource::where('id', $id)->with(['relations' =>  function ($query) use ($type) {
                $query->where('resourceable_type', $type);
            }
            ])->get();
        } else {
            $data =  Resource::where('id', $id)->with(['relations' => function( $q ){
                $q->select(['resources.id', 'resources.title', 'resources.type']);
            }])->first();
        }

	    return $data;
    }
}
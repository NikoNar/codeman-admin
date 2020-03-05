<?php

namespace Codeman\Admin\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Http\Request;
use Codeman\Admin\Models\BaseModel;
use Codeman\Admin\Models\Category;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function changeFeaturedImage(Request $request)
    {
    	$id = $request->has('id') ? $request->get('id') : null;
    	$modelName = $request->has('model') ? $request->get('model') : null;
    	$thumbnail = $request->has('thumbnail') ? $request->get('thumbnail') : null;
    	if($modelName && $id && $thumbnail)
    	{
    		$model = $this->getModel($modelName);
    		$updated = $model->find($id)->update(['thumbnail' => $thumbnail]);
    		if($updated){
    			return response()->json(['status' => 'success']);
    		}
    	}
    	return response()->json(['status' => 'false']);
    }

    public function getDatesOfResources($model)
    {
        // return $model->select(\DB::raw('YEAR(created_at) year'))
        //            ->pluck('year','year');
    }

    public function searchResource(Request $request)
    {
        $modelName = $request->has('model') ? $request->get('model') : null;
        $searchBy = $request->has('search_by') ? $request->get('search_by') : 'title';

        if($modelName)
        {
            $model = $this->getModel($modelName);
            
            if(!request()->has('query') || request()->get('query') == null)
            {
                $result = $model->where('parent_lang_id', null)->orderBy('created_at', 'DESC')->paginate(10);
            }else{
                $result = $model->where($searchBy, 'LIKE', '%'.request()->get('query').'%')->orderBy('created_at', 'DESC')->paginate(10);
            }
            $viewDirection = 'admin-panel::'.strtolower($modelName).'.parts.listing';

            $returnHTML =  view($viewDirection, [str_plural(strtolower($modelName)) => $result])->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));
        }
        return response()->json(['status' => 'false']);
    }

    public function filterResource(Request $request)
    {
        $modelName = $request->has('model') ? $request->get('model') : null;
        $searchBy = $request->has('search_by') ? $request->get('search_by') : 'title';
        $type = ($request->has('type') && $request->get('type') != 'undefined')? $request->get('type') : null;
        if($modelName)
        {
            $model = $this->getModel($modelName);
            $result = $model->newQuery();

            if(request()->has('search') && request()->get('search') != null)
            {
                $result->where($searchBy, 'LIKE', '%'.request()->get('search').'%');
            }
            if(request()->has('email-search') && request()->get('email-search') != null)
            {
                $result->where('email', 'LIKE', '%'.request()->get('email-search').'%');
            }
            if(request()->has('language') && request()->get('language') != null)
            {
                $result->where('lang',request()->get('language'));
            }
            if(request()->has('brand_name') && request()->get('brand_name') != null)
            {
                $result->where('brand_name', request()->get('brand_name'));
            }

            if(request()->has('created_at') && request()->get('created_at') != null)
            {
                $result->whereBetween('created_at', array(request()->get('created_at').'-01-01 00:00:00', request()->get('created_at').'-12-31 23:59:59'))->first();
            }
            if(request()->has('status'))
            {   
                $result->where('status', 'LIKE', '%'.request()->get('status').'%');
            }
            if(request()->has('category_id'))
            {   
                $category_id = request()->get('category_id');
                if($category_id != '' && $category_id != 0){
                    $result->with('categories')->whereHas('categories', function($query) use ($category_id)
                    {
                         $query->where('categories.id', $category_id);
                    });
                }
            }

            $result->orderBy('order', 'DESC');

            if($type){
                $result->where('type', $type);
            }

            if(request()->has('per-page'))
            {  
                $result = $result->paginate((int) request()->get('per-page'));
            }else{
                $result = $result->paginate(10);
            }
            if($modelName == 'Category'){
                $result = $result->groupBy('parent_id');
            }
            if(request()->ajax()){
                $viewDirection = 'admin-panel::'.strtolower($modelName).'.parts.listing';
                $returnHTML =  view($viewDirection, [Str::plural(strtolower($modelName)) => $result, 'module' =>$type]);
                $returnHTML = $returnHTML->render();
                return response()->json(array('success' => true, 'html' => $returnHTML));
            }
            $viewDirection = 'admin-panel::'.strtolower($modelName).'.index';
            $returnHTML =  view($viewDirection, [Str::plural(strtolower($modelName)) => $result]);

            return $returnHTML;
        }
        return response()->json(['status' => 'false']);
    }

    public function bulkDeleteResource(Request $request)
    {
        
        $modelName = $request->has('model') ? $request->get('model') : null;
        if($modelName)
        {
            $model = $this->getModel($modelName);
          
            if($request->has('ids'))
            {
                $resources = $model->find($request->get('ids'));
                if($resources && !$resources->isEmpty()){
                    $ids = [];
                    foreach ($resources as $key => $resource) {
                        $resource->delete();
                        $ids[] = $resource->id;
                    }
                    $result = $model->where('parent_lang_id', null)->orderBy('created_at', 'DESC')->paginate(10);
                    $viewDirection = 'admin-panel::'.strtolower($modelName).'.parts.listing';
                    $returnHTML =  view($viewDirection, [str_plural(strtolower($modelName)) => $result])->render();
                    return response()->json(array('success' => true, 'html' => $returnHTML));
                }
            }
        }
        return response()->json(['status' => 'false']);
    }

    public function updateOrder(Request $request)
    {
        $modelName = $request->has('model') ? $request->get('model') : null;
        if($modelName)
        {
            $model = $this->getModel($modelName);

            if($request->has('ids'))
            {
                $items = $model->whereIn('id', $request->get('ids'))->select('id', 'order')->get();
                $min_order_number = $items->min('order')  == 0 ? 1 : $items->min('order');

                foreach ($request->get('ids') as $key => $id) {
                    $model->find($id)->update(['order' => $min_order_number]);
                    ++$min_order_number;
                }

                return response()->json(array('success' => true, 'html' => $min_order_number));
            }
        }
    }

    public function getCurrentYearResourceNames(Request $request)
    {
        if($request->has('year')){
            $model = $this->getModel(request()->get('model'));

            if(request()->has('year')){

                $resources = $model->where('year', request()->get('year'))->orderBy('created_at', 'DESC')->get()->pluck('title', 'id')->toArray();
            }else{
                $resources = $model->where('year', date('Y'))->orderBy('created_at', 'DESC')->get()->pluck('title', 'id')->toArray();
            }

            if($films){
                return response()->json(array('success' => true, 'html' => $films));
            }
        }
        return response()->json(array('success' => false));

    }

    // return Instance of Model
    private function getModel($modelName)
    {
       $model = "Codeman\\Admin\\Models\\".$modelName;
       $model = new $model; 
       return $model;
    }

    public function getResourceCategories($type)
    {       
        $categories_model = new Category();
        $result = $categories_model->where('type', $type)->where('parent_id', '=', 0)->orderBy('order', 'DESC')->get();
        if(request()->ajax()){
            $viewDirection = 'admin-panel::layouts.parts.categories_dropdown';
            $returnHTML =  view($viewDirection, ['categories' => $result])->render();
            return response()->json(array('success' => true, 'html' => $returnHTML));

        }
        return $result;
    }

    public function getResourceCategoriesAndNames($type)
    {   
        $categories_model = new Category();
        $model = $this->getModel(ucfirst($type));
        $categories = $categories_model->where('type', $type)->where('parent_id', '=', 0)->orderBy('order', 'DESC')->get();
        $resource_names = $model->select('id', 'title')->orderBy('title', 'DESC')->get()->toArray();
        if(request()->ajax()){
            
            $categories_view_direction = 'admin-panel::layouts.parts.categories_dropdown';
            $returnHTML =  view($categories_view_direction, ['categories' => $categories, 'selected'=>[request()->get('category')]])->render();
            return response()->json(array('success' => true, 'categories' => $returnHTML, 'names' => $resource_names));

        }
        return false;
    }
}

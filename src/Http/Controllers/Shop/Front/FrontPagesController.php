<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Codeman\Admin\Models\Resource;
use Codeman\Admin\Models\Category;
use App\Models\Brand;
use App\Models\Product;

class FrontPagesController extends Controller
{
    public function __construct(Resource $resource, Category $category)
    {
    	// $this->middleware('web');
    	$this->lang = \App::getLocale();
    	$this->resource = $resource;
        $this->category = $category;
    }

    public function blog_article($slug)
    {

        $resource = $this->resource->where('type', 'blog')
        ->with('categories', 'relations', 'metas')
        ->where('lang', $this->lang)
        ->where('status', 'published')
        ->where('slug', $slug)
        ->first();
        
        if(!$resource){
            return abort(404);
        }

        $related_resources = null;
        if(isset($resource->categories))
        {
            $resource_cat_ids = [];
            foreach ($resource->categories as $key => $category) {
                $resource_cat_ids[] = $category->id;
                $related_resources[] = $category->relatedResources;
            }
            $rel_res_array = [];
            if($related_resources  && !empty($related_resources)){
                foreach ($related_resources as $key => $array) {
                    if($array && !empty($array) ){
                        foreach ($array as $key => $res) {
                            if($res->id != $resource->id){
                                $rel_res_array[$res->id] = $res;
                            }
                        }
                    }
                    
                }
            }
            $related_resources = $rel_res_array;

            $decoded_resourcemetas = [];
            if($related_resources && !empty($related_resources)){
                foreach ($related_resources as $key => $rel_resource) {
                    if(isset($rel_resource->metas) && !empty($rel_resource->metas)){
                        foreach($rel_resource->metas as $key => $meta) {
                            if(isJson($meta->value)){
                                $decoded_resourcemetas[$meta->key] = json_decode($meta->value, true);
                            } else {
                                $decoded_resourcemetas[$meta->key] = $meta->value;
                            }
                        }            
                    }
                    $rel_resource->setAttribute('meta', $decoded_resourcemetas);
                }
            }
        }

        $decoded_resourcemetas = [];
        if(isset($resource->metas) && !empty($resource->metas)){
            foreach($resource->metas as $key => $meta) {
                if(isJson($meta->value)){
                    $decoded_resourcemetas[$meta->key] = json_decode($meta->value, true);
                } else {
                    $decoded_resourcemetas[$meta->key] = $meta->value;
                }
            }            
        }
        $resource->setAttribute('meta', $decoded_resourcemetas);

        return view('single-blog',[
            'resource' => $resource,
            'related_resources' => $related_resources
        ]);

    	
        // return \Cache::remember($this->lang.'/blog/'.$slug, 3600, function () use($resource, $related_resources) {
        //     return view('single-blog', [
        //         'resource' => $resource,
        //         'related_resources' => $related_resources
        //     ])->render();
        // });
    }

    public function brands_page(Brand $brand_model)
    {
        $brands = $brand_model
        ->select('id', 'title', 'first_letter', 'slug')
        ->where('lang', $this->lang)
        ->where('status', 'published')
        ->orderBy('first_letter', 'ASC')
        ->orderBy('title', 'ASC')
        ->get()
        ->groupBy('first_letter')
        ->toArray();

        return view('brands-page', ['brands' => $brands]);
    }

    public function home(Brand $brand_model)
    {
        $brands = $brand_model
        ->select('id', 'title', 'logo', 'slug')
        ->where('lang', $this->lang)
        ->where('status', 'published')
        ->orderBy('order', 'ASC')
        ->get()->toArray();

        $product_main_categories = $this->category
        ->select('id', 'title', 'thumbnail', 'slug')
        ->where('type', 'products')
        ->where('lang', $this->lang)
        ->where('status', 'published')
        ->orderBy('order', 'ASC')
        ->where('parent_id', 0)
        ->get()->toArray();


        $categories_products = $this->category
        ->select('id', 'title')
        ->where('type', 'products')
        ->where('lang', $this->lang)
        ->where('status', 'published')
        ->orderBy('order', 'ASC')
        ->where('parent_id', 0)
        ->has('products')
        ->with('products')
        ->get();

        dd($categories_products);
    }

    // public function case_study_article($slug)
    // {
    //     $resource = $this->resource->where('type', 'casestudies')
    //     ->with('categories', 'relations', 'metas')
    //     ->where('lang', $this->lang)
    //     ->where('status', 'published')
    //     ->where('slug', $slug)
    //     ->first();

    //     if(!$resource){
    //         return abort(404);
    //     }

    //     $related_resources = null;

    //     if(isset($resource->categories) && isset($resource->categories[0]))
    //     {
    //         $related_resources = $resource->categories[0]->relatedResources->where('id', '!=', $resource->id);

    //         $decoded_resourcemetas = [];
    //         if($related_resources && !empty($related_resources)){
    //             foreach ($related_resources as $key => $rel_resource) {
    //                 if(isset($rel_resource->metas) && !empty($rel_resource->metas)){
    //                     foreach($rel_resource->metas as $key => $meta) {
    //                         if(isJson($meta->value)){
    //                             $decoded_resourcemetas[$meta->key] = json_decode($meta->value, true);
    //                         } else {
    //                             $decoded_resourcemetas[$meta->key] = $meta->value;
    //                         }
    //                     }            
    //                 }
    //                 $rel_resource->setAttribute('meta', $decoded_resourcemetas);
    //             }
    //         }
    //     }
    //     $decoded_resourcemetas = [];
    //     if(isset($resource->metas) && !empty($resource->metas)){
    //         foreach($resource->metas as $key => $meta) {
    //             if(isJson($meta->value)){
    //                 $decoded_resourcemetas[$meta->key] = json_decode($meta->value, true);
    //             } else {
    //                 $decoded_resourcemetas[$meta->key] = $meta->value;
    //             }
    //         }            
    //     }
    //     $resource->setAttribute('meta', $decoded_resourcemetas);
    //     return view('single-case_study', [
    //         'related_resources' => $related_resources,
    //         'resource' => $resource,
    //     ])->render();

    // }

    // public function training_article($slug)
    // {
    //     $resource = $this->resource->where('type', 'trainings')
    //     ->with('categories', 'relations', 'metas')
    //     ->where('lang', $this->lang)
    //     ->where('status', 'published')
    //     ->where('slug', $slug)
    //     ->first();
        
    //     if(!$resource){
    //         return abort(404);
    //     }

    //     $decoded_resourcemetas = [];
    //     if(isset($resource->metas) && !empty($resource->metas)){
    //         foreach($resource->metas as $key => $meta) {
    //             if(isJson($meta->value)){
    //                 $decoded_resourcemetas[$meta->key] = json_decode($meta->value, true);
    //             } else {
    //                 $decoded_resourcemetas[$meta->key] = $meta->value;
    //             }
    //         }            
    //     }
    //     $resource->setAttribute('meta', $decoded_resourcemetas);

    //     return view('single-training', [
    //         'resource' => $resource,
    //     ])->render();

    // }

    // public function get_portfolio_item($id)
    // {
    //     $resource = $this->resource->where('type', 'portfolio')
    //     ->with('categories', 'relations', 'metas')
    //     ->where('lang', $this->lang)
    //     ->where('status', 'published')
    //     ->where('id', $id)
    //     ->first();
        
    //     if(!$resource){
    //         return abort(404);
    //     }

        

    //     $related_resources = null;
    //     if(isset($resource->categories))
    //     {
    //         $resource_cat_ids = [];
    //         foreach ($resource->categories as $key => $category) {
    //             $resource_cat_ids[] = $category->id;
    //             $related_resources[] = $category->relatedResources;
    //         }
    //         $rel_res_array = [];
    //         if($related_resources  && !empty($related_resources)){
    //             foreach ($related_resources as $key => $array) {
    //                 if($array && !empty($array) ){
    //                     foreach ($array as $key => $res) {
    //                         if($res->id != $resource->id){
    //                             $rel_res_array[$res->id] = $res;
    //                         }
    //                     }
    //                 }
                    
    //             }
    //         }
    //         $related_resources = $rel_res_array;

    //         $decoded_resourcemetas = [];
    //         if($related_resources && !empty($related_resources)){
    //             foreach ($related_resources as $key => $rel_resource) {
    //                 if(isset($rel_resource->metas) && !empty($rel_resource->metas)){
    //                     foreach($rel_resource->metas as $key => $meta) {
    //                         if(isJson($meta->value)){
    //                             $decoded_resourcemetas[$meta->key] = json_decode($meta->value, true);
    //                         } else {
    //                             $decoded_resourcemetas[$meta->key] = $meta->value;
    //                         }
    //                     }            
    //                 }
    //                 $rel_resource->setAttribute('meta', $decoded_resourcemetas);
    //             }
    //         }
    //     }

    //     $decoded_resourcemetas = [];
    //     if(isset($resource->metas) && !empty($resource->metas)){
    //         foreach($resource->metas as $key => $meta) {
    //             if(isJson($meta->value)){
    //                 $decoded_resourcemetas[$meta->key] = json_decode($meta->value, true);
    //             } else {
    //                 $decoded_resourcemetas[$meta->key] = $meta->value;
    //             }
    //         }            
    //     }
    //     $resource->setAttribute('meta', $decoded_resourcemetas);

    //     return response()->json([
    //         'status' => 200,
    //         'html' => view('layouts.portfolio-modal', [
    //             'resource' => $resource,
    //             'related_resources' => $related_resources
    //         ])->render()
    //     ]);
    // }
}

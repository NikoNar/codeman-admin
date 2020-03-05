<?php

namespace Codeman\Admin\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Codeman\Admin\Models\Language;
use Codeman\Admin\Models\Module;
use Codeman\Admin\Models\Resource;
use Illuminate\Http\Request;
use Codeman\Admin\Models\Page;
use Codeman\Admin\Models\Lecturer;
use Codeman\Admin\Models\Review;
use Codeman\Admin\Models\Portfolio;
use Codeman\Admin\Models\Program;
use Codeman\Admin\Models\Category;
use Codeman\Admin\Models\Pagemeta;
use Codeman\Admin\Models\Setting;
use Codeman\Admin\Models\Service;
use Codeman\Admin\Models\File;
use Illuminate\Support\Facades\View;
use DB;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Codeman\Admin\Services\CRUDService;
// use App\Models\Product;
// use App\Models\Category;

class PagesController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Page $page, Pagemeta $pagemeta)
    {
//        \App::setLocale('hy');
//        dd(LaravelLocalization::getCurrentLocale());
//        dd(session()->all(), 'const');
        $this->lang = \App::getLocale();
        // $this->lang = 'en';
        $this->page = $page;
        $this->pagemeta = $pagemeta;
//    	dd($this->lang);
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug = null)
    {
        $pageObject = null;
        $index_page_id = Setting::select('value')->where('key', 'index')->first()['value'];
        $url = explode('/', $slug);
        $default_lang = Language::orderBy('order')->first();
        // $def_land_id  = $default_lang->id;
        $def_land_code  = $default_lang->code;
        // $this_lang_id = Language::where('code', $this->lang)->first()->id;

        if(count($url) > 1){
            $slug = $url[count($url) - 1];
        }
        if(!$slug){

            $pageObject = $this->page->where('lang', $this->lang)
            ->whereStatus('published')
            ->where(function($query) use($index_page_id){
                $query->where('id', $index_page_id)->orWhere('parent_lang_id', $index_page_id);
            })
            ->with('metas')
            ->with(['pagetemplate' => function($q){
                $q->select('id','slug');
            }])
            ->first();
            
            if(!$pageObject){

                $pageObject = $this->page->where('lang', $this->lang)
                ->whereStatus('published')
                ->where(function($query){
                    $query->join('pages as p','pages.parent_lang_id', '=', 'p.parent_lang_id')->where('lang', $this->lang);
                })
                ->with('metas')
                ->with(['pagetemplate' => function($q){
                    $q->select('id','slug');
                }])
                ->first();
            }

        }

        if(!$pageObject){

            $pageObject = $this->page->whereSlug($slug)->whereLang($this->lang)
            ->whereStatus('published')
            ->with('metas')
            ->with(['pagetemplate' => function($q){
                $q->select('id','slug');
            }])
            ->first();
            
        }

        if(!$pageObject && session()->has('page_id')){

            if( null != $prevPage = $this->page->where('id', session()->get('page_id') )->first()){
                if($prevPage->parent_lang_id){

                    $pageObject = $this->page->where('lang', $this->lang)
                    ->whereStatus('published')
                    ->where(function ($query) use($prevPage){
                        $query->where('parent_lang_id', $prevPage->parent_lang_id)
                            ->orWhere('id', $prevPage->parent_lang_id);
                    })
                    ->with('metas')
                    ->with(['pagetemplate' => function($q){
                        $q->select('id','slug');
                    }])
                    ->first();

                } else {

                    $pageObject = $this->page->where('parent_lang_id', $prevPage->id)
                    ->whereStatus('published')
                    ->where('lang', $this->lang)
                    ->with('metas')
                    ->with(['pagetemplate' => function($q){
                        $q->select('id','slug');
                    }]) 
                    ->first();

                }

                if($pageObject){
                    if(($pageObject->id != $index_page_id && $slug)  && $pageObject->parent_lang_id != $index_page_id && $slug){
                        $url = buildUrl($pageObject);
                        return redirect()->to($url);
                    }
                }
            }
        }



        if($pageObject){
            // $idex = Page::where('id', $index_page_id)->first();
            if(($pageObject->id == $index_page_id && $slug)  ||  ($pageObject->parent_lang_id == $index_page_id && $slug) /*|| ($pageObject->parent_lang_id === $idex->parent_lang_id && $slug)*/){
                // $lang_code = Language::where('id', $pageObject->language_id)->first()->code;
                $lang_code = $pageObject->lang;
                if($lang_code == $def_land_code){
                    return redirect()->to('/');
                } else {
                    return redirect()->to('/'.$lang_code);
                }
            }
            // For making a menu using parent and chiled pages
            // $submenu = $this->page->where('parent_id', $pageObject->id)
            // ->where('lang', $this->lang)
            // ->select('id', 'order', 'title', 'slug', 'parent_id')
            // ->orderBy('order', 'DESC')->get();

            // $siblingmenu = null;
            // $parentmenu = null;
            // if($pageObject->parent_id){
            // 	$siblingmenu = $this->page->where('parent_id', $pageObject->parent_id)
            // 	->where('lang', $this->lang)
            // 	->select('id', 'order', 'title', 'slug', 'parent_id')
            // 	->orderBy('order', 'DESC')->get();

            // 	$parent_page = $this->page->where('id', $pageObject->parent_id)
            // 	->where('lang', $this->lang)
            // 	->select('id', 'order', 'title', 'slug', 'parent_id')
            // 	->orderBy('order', 'DESC')->first();
            // 	if(!empty($parent_page) && $parent_page->parent_id != null){
            // 		$parentmenu = $this->page->where('parent_id', $parent_page->parent_id)
            //  	->where('lang', $this->lang)
            //  	->select('id', 'order', 'title', 'slug', 'parent_id')
            //  	->orderBy('order', 'DESC')->get();
            // 	}
            // }
            // if($siblingmenu != null && !$siblingmenu->isEmpty()){
            // 	if($siblingmenu->count() <= 1){
            // 		$siblingmenu = null;
            // 	}
            // }
            //END For making a menu using parent and chiled pages

            // if(!$submenu->isEmpty()){
            // 	$pageObject = $this->page->where('slug', $submenu[0]->slug)->whereStatus('published')->first();
            // }
            $pagemetas = null;
            if(isset($pageObject->metas)){
                // $pagemetas = $this->getPageMetas($pageObject->id);
                $pagemetas = $pageObject->metas->pluck('value', 'key')->toArray();
            }

            if($pagemetas){
                $pageObject->setAttribute('meta', $pagemetas);
            }

            if(!empty($pageObject)){
                $content = json_decode($pageObject->description);
            }


            if(array_key_exists('attachments', $pagemetas) && !empty($pagemetas['attachments'])){
                $attachments = json_decode($pagemetas['attachments'], true);
                
                foreach($attachments as $type => $val){
                    if($val == 'all'){
                        $resource = Resource::where('type', $type)
                        ->with('metas')
                        ->with('categories')
                        ->where('status', 'published')
                        ->where('lang', $this->lang)
                        ->orderBy('order', 'DESC')
                        ->get();
                    } else {
                        $resource = Resource::whereIn('id', explode(',', $val))
                        ->with('metas')
                        ->with('categories')
                        ->where('status', 'published')
                        ->where('lang', $this->lang)
                        ->orderBy('order', 'DESC')
                        ->get();
                        
                    }

                    $resourseWithMetas = [];
                    $resource_ids = $resource->pluck('id')->toArray();
                    // $CRUD = new CRUDService($resource, $default_lang);
                    // $resourcemetas = $CRUD->getPageMetasByResourceIds($resource_ids);
                    // dump($resourcemetas);
                    // dd($resourcemetas);
                    foreach($resource as $item){
                        // $resourcemetas = $CRUD->getPageMetas($item->id);
                        $resourcemetas = $item->metas->pluck('value', 'key')->toArray();
                        // dd($resourcemetas);
                        $decoded_resourcemetas = [];
                        foreach($resourcemetas as $key => $value) {
                            if(isJson($value)){
                                $decoded_resourcemetas[$key] = json_decode($value, true);
                            } else {
                                $decoded_resourcemetas[$key] = $value;
                            }
                        }

                        $item->setAttribute('meta', $decoded_resourcemetas);
                        $resourseWithMetas[] = $item;
                    }

                    $pageObject[$type] = $resourseWithMetas;
                    
                    $resource_categories = Category::where('type', $type)->where('lang', $this->lang)->where('status', 'published')->orderBy('order', 'ASC')->get();
                    $pageObject->setAttribute($type.'_categories', $resource_categories);
                }
            }

            if($pageObject->template){
                // $template = Module::where('id', $pageObject->template)->first()->slug;
                $template = $pageObject->pagetemplate->slug;
                // dd($template);
            } else {
                $template = null;
            }


            session()->put('page_id', $pageObject->id);
            session()->put('model_type', 'page');
            //session()->flush();
            session()->save();
           

            if ($template && View::exists($template)) {
                return view($template, [
                    'page' => $pageObject,
                    // 'submenu' => $submenu,
                    // 'siblingmenu' => $siblingmenu,
                    // 'parentmenu' => $parentmenu,
                ] );
            } else {

                return View::exists('default')? view('default', ['page' => $pageObject]) : abort(404);

            }
        }
        // return redirect('/');
        abort(404);
    }

    private function getPageMetas($page_id)
    {
        return $this->pagemeta->where('page_id', $page_id)->select('key', 'value')->pluck('value', 'key')->toArray();
    }
}
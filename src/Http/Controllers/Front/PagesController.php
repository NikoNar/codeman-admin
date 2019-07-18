<?php

namespace Codeman\Admin\Http\Controllers\Front;

use App\Http\Controllers\Controller;
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
//        dd(session()->get('prev_lang'), 'ctrl');
        $pageObject = null;
        $index_page_id = Setting::select('value')->where('key', 'index')->first()['value'];
        $url = explode('/', $slug);
        $default_lang = Language::orderBy('order')->first();
        $def_land_id  = $default_lang->id;
        $def_land_code  = $default_lang->code;
        $this_lang_id = Language::where('code', $this->lang)->first()->id;

        if(count($url) > 1){
            $slug = $url[count($url) - 1];
        }

        if(!$slug){
            $pageObject = $this->page->where('language_id', $this_lang_id)->whereStatus('published')->where(function($query) use($index_page_id){
                $query->where('id', $index_page_id)->orWhere('parent_lang_id', $index_page_id);
            })->first();
            if(!$pageObject){
                $pageObject = $this->page->where('language_id', $this_lang_id)->whereStatus('published')->where(function($query) use($this_lang_id){
                    $query->join('pages as p','pages.parent_lang_id', '=', 'p.parent_lang_id')->where('language_id', $this_lang_id);
                })->first();
            }

        }

        if(!$pageObject){
            $pageObject = $this->page->whereSlug($slug)->whereLanguageId($this_lang_id)->whereStatus('published')->first();
        }

        if(!$pageObject && session()->has('page_id')){
            if( null != $prevPage = $this->page->where('id', session()->get('page_id') )->first()){
                if($prevPage->parent_lang_id){
                    $pageObject = $this->page->where('language_id', $this_lang_id)->where(function ($query) use($prevPage){
                        $query->where('parent_lang_id', $prevPage->parent_lang_id)
                            ->orWhere('id', $prevPage->parent_lang_id);
                    })->first();
                } else {
                    $pageObject = $this->page->where('parent_lang_id', $prevPage->id)->where('language_id', $this_lang_id)->first();

                }

                if($pageObject){
                    if(($pageObject->id != $index_page_id && $slug)  && $pageObject->parent_lang_id != $index_page_id && $slug){
                        $url = buildUrl($pageObject);
                        return redirect()->to($url);
                    }
                }
            }
        }
//        dd('1', $pageObject);
//        if($this->lang != $def_land_code){
//            if(!$slug){
//                $pageObject = $this->page->where('parent_lang_id', $index_page_id)->where('language_id', '=', $this_lang_id)->whereStatus('published')->first();
//            }else{
//                $pageObject = $this->page->where('slug', $slug)->where('language_id', '=', $this_lang_id)->whereStatus('published')->first();
//            }
//            if(!$pageObject){
//                if(!$slug){
//                    $parent_page_id = $this->page->where('id', $index_page_id)->whereStatus('published')->pluck('id')->first();
//                }else{
//                    $parent_page_id = $this->page->where('slug', $slug)->whereStatus('published')->pluck('id')->first();
//                }
//                $pageObject = $this->page->where('parent_lang_id', $parent_page_id)->whereStatus('published')->select('id', 'parent_id', 'slug')->first();
//                if($pageObject){
//                    $url = buildUrl($pageObject);
//                    return redirect()->to($url);
//                }
//            }
//        }else{
//            if(!$slug){
//                $pageObject = $this->page->where('id', $index_page_id)->where('language_id', $def_land_id)->whereStatus('published')->first();
//            }else{
//                $pageObject = $this->page->where('slug', $slug)->where('language_id', $def_land_id)->whereStatus('published')->first();
//            }
//            if(!$pageObject){
//                if(!$slug){
//                    $child_page_parent_id = $this->page->where('id', $index_page_id)->whereStatus('published')->pluck('parent_lang_id')->first();
//                }else{
//                    $child_page_parent_id = $this->page->where('slug', $slug)->whereStatus('published')->pluck('parent_lang_id')->first();
//                }
//                if($child_page_parent_id > 0){
//                    $pageObject = $this->page->where('id', $child_page_parent_id)->whereStatus('published')->select('id', 'parent_id', 'slug')->first();
//                }
//
//                if($pageObject){
//                    $url = buildUrl($pageObject);
//                    return redirect()->to($url);
//                }
//
//            }
//        }



        if($pageObject){
//            dd($pageObject);
            $idex = Page::where('id', $index_page_id)->first();
            if(($pageObject->id == $index_page_id && $slug)  ||  ($pageObject->parent_lang_id == $index_page_id && $slug) || ($pageObject->parent_lang_id === $idex->parent_lang_id && $slug)){
                return redirect()->to('/');
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
            $pagemetas = $this->getPageMetas($pageObject->id);
            if($pagemetas){
                $pageObject->setAttribute('meta', $pagemetas);
            }


            if(!empty($pageObject)){
                $content = json_decode($pageObject->description);
            }

            // dd(json_decode($pageObject->description));
            // dd($pageObject);

            if(array_key_exists('attachments', $pagemetas) && !empty($pagemetas['attachments'])){
                $attachments = json_decode($pagemetas['attachments'], true);
                foreach($attachments as $type => $val){
                    if($val == 'all'){
                        $pageObject[$type] = Resource::where('type', $type)->get();
                    } else {
                        $pageObject[$type] = Resource::whereIn('id', explode(',', $val))->get();
                    }
                }

            }
            if($pageObject->template){
                $template = Module::where('id', $pageObject->template)->first()->slug;
            } else {
                $template = null;
            }

//            dd($pageObject->id);
            session()->put('page_id', $pageObject->id);
            session()->put('model_type', 'page');
//            session()->flush();
            session()->save();
//            dd(session()->all());
            if ($template && View::exists($template)) {
                return view($template, [
                    'page' => $pageObject,
                    // 'submenu' => $submenu,
                    // 'siblingmenu' => $siblingmenu,
                    // 'parentmenu' => $parentmenu,

                    
                ] );
            } else {

                return view('default', [
                    'page' => $pageObject,
                    // 'submenu' => $submenu,
                    // 'siblingmenu' => $siblingmenu,
                    // 'parentmenu' => $parentmenu,
                ] );

            }
        }
        // return redirect('/');
        abort(404);
    }

    public function home(){
        // return $this->index('home');
        $reviews = Review::where('lang', $this->lang)->get();
        return view('home', compact('reviews'));
    }



    private function getPageMetas($page_id)
    {
        return $this->pagemeta->where('page_id', $page_id)->select('key', 'value')->pluck('value', 'key')->toArray();
    }
}

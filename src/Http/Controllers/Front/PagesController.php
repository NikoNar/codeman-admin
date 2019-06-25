<?php

namespace Codeman\Admin\Http\Controllers\Front;

use App\Http\Controllers\Controller;
use Codeman\Admin\Models\Language;
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
//        dd(session()->all(), 'ctrl');
//        dd(LaravelLocalization::getCurrentLocale());
        $index_page_id = Setting::select('value')->where('key', 'index')->first()['value'];
    	$url = explode('/', $slug);
        $default_lang = Language::orderBy('order')->first();
        $def_land_id  = $default_lang->id;
        $def_land_code  = $default_lang->code;
        $this_lang_id = Language::where('code', $this->lang)->first()->id;


    	if(count($url) > 1){

    		$slug = $url[count($url) - 1];

    	}
    	if($this->lang != $def_land_code){
//            dd('f');
            if(!$slug){
                $pageObject = $this->page->where('parent_lang_id', $index_page_id)->where('language_id', '=', $this_lang_id)->whereStatus('published')->first();
            }else{
                $pageObject = $this->page->where('slug', $slug)->where('language_id', '=', $this_lang_id)->whereStatus('published')->first();
            }
    		if(!$pageObject){
                if(!$slug){
                    $parent_page_id = $this->page->where('id', $index_page_id)->whereStatus('published')->pluck('id')->first();
                }else{
	    		    $parent_page_id = $this->page->where('slug', $slug)->whereStatus('published')->pluck('id')->first();
                }
                $pageObject = $this->page->where('parent_lang_id', $parent_page_id)->whereStatus('published')->select('id', 'parent_id', 'slug')->first();
	    		if($pageObject){
	    			$url = buildUrl($pageObject);
	    			return redirect()->to($url);
	    		}
    		}
    	}else{

            if(!$slug){
                $pageObject = $this->page->where('id', $index_page_id)->where('language_id', $def_land_id)->whereStatus('published')->first();
            }else{
              $pageObject = $this->page->where('slug', $slug)->where('language_id', $def_land_id)->whereStatus('published')->first();
            }
            if(!$pageObject){
                if(!$slug){
                    $child_page_parent_id = $this->page->where('id', $index_page_id)->whereStatus('published')->pluck('parent_lang_id')->first();
                }else{
                    $child_page_parent_id = $this->page->where('slug', $slug)->whereStatus('published')->pluck('parent_lang_id')->first();
                }
                if($child_page_parent_id > 0){
                    $pageObject = $this->page->where('id', $child_page_parent_id)->whereStatus('published')->select('id', 'parent_id', 'slug')->first();
                }

                if($pageObject){
                    $url = buildUrl($pageObject);
                    return redirect()->to($url);
                }
                
            }
        }

             // dd($pageObject);


    	if($pageObject){
            if(($pageObject->id == $index_page_id && $slug) /* || $pageObject->parent_lang_id === $index_page_id && $slug*/ ){
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
	    	if($pageObject){
	    		$pagemetas = $this->getPageMetas($pageObject->id);
		    	if($pagemetas){
		    		$pageObject->setAttribute('meta', $pagemetas);
		    	}
	    	}

            if(!empty($pageObject)){
                $content = json_decode($pageObject->description);
            }

            // dd(json_decode($pageObject->description));
            // dd($pageObject);
    

            if ($pageObject->template && View::exists($pageObject->template)) {
                if($pageObject->template == 'programs') {
                    $programs = Program::where('lang', $this->lang)->get();
                    // dd($programs);
                        return view($pageObject->template, [
                        'page' => $pageObject,
                        'programs' => $programs,
                        
                    ] );
                        
//                } elseif($pageObject->template == 'interior'|| $pageObject->template == 'graphic'){
//                    $lecturersIds = json_decode($pageObject->lecturers);
//
//                    if($lecturersIds){
//                        $lecturers = [];
//                    foreach($lecturersIds as $key => $id){
//                        array_push($lecturers, Lecturer::find($id));
//                    }
//
//                    $pageObject->lecturers = $lecturers;
//                    }
//
//                    $portfolios = Portfolio::whereHas('categories', function($query) use($pageObject){
//                        $query->where('categories.slug', $pageObject->template);
//                    })->where('lang', $this->lang)->get();
//                    $pageObject->portfolios = $portfolios;
//                    // dd($pageObject);
//
//
//
//                } elseif ($pageObject->template == 'lecturers' ) {
//                    $lecturers = Lecturer::with('categories')->whereHas('categories', function($query){
//                        $query->where('slug', '!=', 'staff');
//                    })->where('lang', $this->lang)->orderBy('order')->get();
//
//                    $categories = Category::whereHas('lecturers')->select(DB::raw('title_'.$this->lang.' as title'))->where('title_en', '!=', 'staff')->get();
//                    $page = $pageObject;
//                    return view('lecturers', compact('lecturers', 'categories', 'page'));

//                } elseif( $pageObject->template == 'staff'){
//
//                    $staff = Lecturer::with('categories')->whereHas('categories', function($query){
//                        $query->where('slug', '=', 'staff');
//                    })->where('lang', $this->lang)->orderBy('order')->get();
//                    $page = $pageObject;
//                    return view('staff', compact('staff', 'page'));

//                } elseif( $pageObject->template == 'portfolio'){
//                    $pageObject['portfolios'] = Portfolio::with('categories')->where('lang', $this->lang)->orderBy('order')->get();
//                    $pageObject['categories'] = Category::whereHas('portfolios')->select('title_'.$this->lang)->get();
                } elseif( $pageObject->template == 'about-us'){

                    // $pageObject['lecturers'] = Lecturer::with('categories')->whereHas('categories', function($query){
                    // // $query->where('title', '!=', 'staff');
                    // })->where('lang', $this->lang)->orderBy('order')->get();

                    // $pageObject['categories'] = Category::whereHas('lecturers')->select(DB::raw('title_'.$this->lang.' as title'))->get();

                    $pageObject['staff'] = Lecturer::where(['language_id' => $this_lang_id, 'status'=>'published'])->get();
                    $pageObject['partners'] = Review::where(['language_id'=> $this_lang_id, 'status'=>'published'])->get();

                    // dd($pageObject);
                } elseif( $pageObject->template == 'index'){
                   

                    $pageObject['partners'] = Review::where(['language_id'=> $this_lang_id, 'status'=>'published'])->get();
                    $pageObject['services'] = Service::where(['language_id'=> $this_lang_id, 'status'=>'published'])->orderBy('order')->limit(3)->get();

                } elseif( $pageObject->template == 'testimonials'){
                    $reviews = Review::where('language_id', $this_lang_id)->get();
                    $page = $pageObject;
                    return view('testimonials', compact('reviews', 'page'));
                } elseif( $pageObject->template == 'services'){
                    $pageObject['services'] = Service::where(['language_id' => $this_lang_id, 'status'=>'published'])->get();
                } elseif( $pageObject->template == 'publications'){
                    $pageObject['publications'] = File::where(['language_id' => $this_lang_id, 'status'=>'published'])->where('year',  now()->year)->whereHas('categories', function($query){
                        $query->where('categories.slug', 'financial-reports');
                    })->get();
//                    $pageObject['companies'] = File::where('lang', $this->lang)->whereHas('categories', function($query){
//                        $query->where('categories.slug', 'company');
//                    })->get();
                }elseif( $pageObject->template == 'useful-files'){
                    $pageObject['useful_files'] = File::where(['language_id' => $this_lang_id, 'status'=>'published'])->whereHas('categories', function($query){
                        $query->where('categories.slug', 'useful-file');
                    })->get();
                }


                return view($pageObject->template, [
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

    // public function testimonials(){
    //     $reviews = Review::where('lang', $this->lang)->get();
    //     return view('testimonilas', compact('reviews'));
    // }

    // public function lecturers(){
    //     $lecturers = Lecturer::with('categories')->whereHas('categories', function($query){
    //         $query->where('title', '!=', 'staff');
    //     })->orderBy('order')->get();
    //     $categories = Category::whereHas('lecturers')->select('title')->where('title', '!=', 'staff')->get();
    //     // dd($categories);
    //     return view('lecturers', compact('lecturers', 'categories'));
    // }

    private function getPageMetas($page_id)
    {
    	return $this->pagemeta->where('page_id', $page_id)->select('key', 'value')->pluck('value', 'key')->toArray();
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use App\Models\Pagemeta;
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
    	// $this->lang = \LaravelLocalization::getCurrentLocale();
    	$this->lang = 'en';
        $this->page = $page;
    	$this->pagemeta = $pagemeta;
    }


    /**
    	* Show the application dashboard.
    	*
    	* @return \Illuminate\Http\Response
    */
    public function index($slug = 'home')
    {
    	$url = explode('/', $slug);
    	if(count($url) > 1){
    		$slug = $url[count($url) - 1];
    	}

    	if($this->lang != 'en'){
    		$pageObject = $this->page->where('slug', $slug)->where('lang', '!=', 'en')->whereStatus('published')->first();
    		
    		if(!$pageObject){
	    		$parent_page_id = $this->page->where('slug', $slug)->whereStatus('published')->pluck('id')->first();
	    		$pageObject = $this->page->where('parent_lang_id', $parent_page_id)->whereStatus('published')->select('id', 'parent_id', 'slug')->first();
	    		if($pageObject){
	    			$url = buildUrl($pageObject);
	    			return redirect()->to($url);
	    		}
    		}
    	}else{
    		$pageObject = $this->page->where('slug', $slug)->where('lang', 'en')->whereStatus('published')->first();
    		if(!$pageObject){
	    		$child_page_parent_id = $this->page->where('slug', $slug)->whereStatus('published')->pluck('parent_lang_id')->first();
    			if($child_page_parent_id > 0){
	    			$pageObject = $this->page->where('id', $child_page_parent_id)->whereStatus('published')->select('id', 'parent_id', 'slug')->first();
    			}
    			if($pageObject){
    				$url = buildUrl($pageObject);
    				return redirect()->to($url);
    			}
    			
    		}
    	}

    	if($pageObject){

	    	$submenu = $this->page->where('parent_id', $pageObject->id)
	    	->where('lang', $this->lang)
	    	->select('id', 'order', 'title', 'slug', 'parent_id')
	    	->orderBy('order', 'DESC')->get();

	    	$siblingmenu = null;
	    	$parentmenu = null;
	    	if($pageObject->parent_id){
		    	$siblingmenu = $this->page->where('parent_id', $pageObject->parent_id)
		    	->where('lang', $this->lang)
		    	->select('id', 'order', 'title', 'slug', 'parent_id')
		    	->orderBy('order', 'DESC')->get();

		    	$parent_page = $this->page->where('id', $pageObject->parent_id)
		    	->where('lang', $this->lang)
		    	->select('id', 'order', 'title', 'slug', 'parent_id')
		    	->orderBy('order', 'DESC')->first();
		    	if(!empty($parent_page) && $parent_page->parent_id != null){
		    		$parentmenu = $this->page->where('parent_id', $parent_page->parent_id)
			    	->where('lang', $this->lang)
			    	->select('id', 'order', 'title', 'slug', 'parent_id')
			    	->orderBy('order', 'DESC')->get();
		    	}
	    	}	    	
	    	if($siblingmenu != null && !$siblingmenu->isEmpty()){
	    		if($siblingmenu->count() <= 1){
	    			$siblingmenu = null;
	    		}
	    	}
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
    		return view('home', [
    			'page' => $pageObject,
                'submenu' => $submenu,
    			'siblingmenu' => $siblingmenu,
    			'parentmenu' => $parentmenu,
            ] );
    	}
    	// return redirect('/');
    	abort(404);
    }

    public function home(){
        return $this->index('home');
    }

    private function getPageMetas($page_id)
    {
    	return $this->pagemeta->where('page_id', $page_id)->select('key', 'value')->pluck('value', 'key')->toArray();
    }
}

<?php

namespace Codeman\Admin\Http\Controllers\Front;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;
use Codeman\Admin\Models\Language;

class LanguagesController extends Controller
{


    public function changeLanguage($lang = null)
    {

        $default_language = Language::orderBy('order')->first()->code;
        $avail_langs = Language::pluck('code')->toArray();

        if(!$lang){
            $lang = $default_language;
        }

        $previous_url = url()->previous();
        $previous_url = explode('/', $previous_url);
        $base_url = url()->to('/');
        $base_url = explode('/', $base_url);

        foreach ($base_url as $key => $value) {
            unset($previous_url[$key]);
        }

        $next_url = [];
        if(isset($previous_url) && is_array($previous_url) && !empty($previous_url)){
            foreach ($previous_url as $key => $value) {
                $next_url[] = $value;
            }
            if(in_array($next_url[0], $avail_langs)){
                unset($next_url[0]);
            }
        }
        $next_request = implode('/', $next_url);
        session()->put('lang', $lang);
        \App::setLocale($lang);

        if($lang == $default_language )
        {
            return redirect()->to('/'.$next_request);
        }
        return redirect()->to('/'.$lang.'/'.$next_request);

    }
}

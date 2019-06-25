<?php

namespace Codeman\Admin\Http\Middleware;

use Closure;
use Illuminate\Routing\Redirector;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;
// use Illuminate\Contracts\Routing\Middleware;

class Language  {

    public function __construct(Application $app, Redirector $redirector, Request $request) {
        $this->app = $app;
        $this->redirector = $redirector;
        $this->request = $request;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $segments = $request->segments();
        $is_url_has_lang = $request->segment(2);
        $current_lang = \App::getLocale();
        $current_lang = session()->get('lang');


        if($is_url_has_lang != 'arm' && $current_lang == 'arm')
        {
            return $this->redirector->to($current_lang.'/'.implode('/', $segments));
        }

        // dd($locale);
        // If the locale is added to to skip_locales array continue without locale
        // if (in_array($locale, $this->app->config->get('app.skip_locales'))) {
        //     // $this->app->setLocale('en');
        //     return $next($request);
        // } else {
        //     dd('aaaa');
        //     // If the locale does not exist in the locales array continue with the fallback_locale
        //     if (! array_key_exists($locale, $this->app->config->get('app.locales'))) {
        //         $segments = $request->segments();
        //         array_unshift($segments, $this->app->config->get('app.fallback_locale'));
        //         // $segments[0] = $this->app->config->get('app.fallback_locale');
        //         return $this->redirector->to(implode('/', $segments));
        //     }
        // }
        // $this->app->setLocale($locale);

        return $next($request);
    }

}

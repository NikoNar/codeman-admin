<?php 
namespace Codeman\Admin;

use Codeman\Admin\Models\Module;
use Codeman\Admin\Models\Setting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Foundation\AliasLoader;
use Codeman\Admin\Http\Middleware\Admin;
use Illuminate\Support\Facades\View;

use Codeman\Admin\Models\Shop\Cart;
/**
 * Codeman\Admin\
 */
class AdminServiceProvider extends ServiceProvider
{
	public function boot()
	{

		
		$this->loadRoutesFrom(__DIR__.'/routes/web.php');
		$this->loadViewsFrom(__DIR__.'/views', 'admin-panel');
		$this->loadMigrationsFrom(__DIR__.'/database/migrations');

		
		// $this->publishes([
  //       	__DIR__.'/path/to/config/courier.php' => config_path('courier.php'),
  //       	 __DIR__.'views' => resource_path('views/vendor/admin'),
  //   	]);
  

		$this->publishes([
       	 	__DIR__.'/assets' => public_path('admin-panel'),
   		 ], 'public');

		$this->publishes([
       		 __DIR__.'/config/images.php' => config_path('images.php'),
		], 'config');

        $this->publishes([
            __DIR__.'/config/images/users' => public_path('images/users'),
        ], 'public');

        $this->publishes([
            __DIR__.'/config/analytics-service-account-credentials.json' => public_path('analytics-service-account-credentials.json'),
        ], 'public');

        $this->publishes([
            __DIR__.'/config/translation-manager.php' => config_path('translation-manager.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/config/auth.php' => config_path('auth.php'),
        ], 'config');

        $this->publishes([
            __DIR__.'/config/analytics.php' => config_path('analytics.php'),
        ], 'config');
			
		$this->publishes([
			__DIR__.'/config/service-account-credentials.json' => storage_path('app/analytics/service-account-credentials.json'),
		], 'storage');

        //Publish Data Filters Congings
        $this->publishes([
             __DIR__.'/config/data-filters.php' => config_path('admin-data-filters.php'),
        ], 'config');

		$translationManagerViewPath = __DIR__.'/views/translation-manager/';
        $this->loadViewsFrom($translationManagerViewPath, 'translation-manager');
        $this->publishes([
            $translationManagerViewPath => base_path('resources/views/vendor/translation-manager'),
        ], 'views');

		AliasLoader::getInstance()->alias('Form', '\Collective\Html\FormFacade');
        AliasLoader::getInstance()->alias('Html', '\Collective\Html\HtmlFacade');
        AliasLoader::getInstance()->alias('JsValidator', '\Proengsoft\JsValidation\Facades\JsValidatorFacade');
        AliasLoader::getInstance()->alias( 'LaravelLocalization' , 'Mcamara\LaravelLocalization\Facades\LaravelLocalization');
		
		AliasLoader::getInstance()->alias( 'Avatar' , 'Laravolt\Avatar\Facade');
		AliasLoader::getInstance()->alias( 'Menu' , 'Codeman\Admin\Menu\Facades\Menu');






        $router = $this->app['router'];
    	$router->pushMiddlewareToGroup('admin', Http\Middleware\Admin::class);
    	$router->pushMiddlewareToGroup('language', Http\Middleware\Language::class);
        $router->aliasMiddleware('role' , \Spatie\Permission\Middlewares\RoleMiddleware::class);
        $router->aliasMiddleware('permission' , \Spatie\Permission\Middlewares\PermissionMiddleware::class);
        $router->aliasMiddleware('role_or_permission' , \Spatie\Permission\Middlewares\RoleOrPermissionMiddleware::class);
  //       $this->app->singleton('Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode',
		// function($app){
  //           return  new Admin($app);
  //       });
        View::composer('admin-panel::layouts.left-menu', function($view){
            $modules = Module::where('module_type', 'module')->orderBy('order','DESC')->get();

            $view->with('modules', $modules);
        });
        
        View::composer(['layouts.app-main', 'layouts.app-login'], function ($view) {
            // $settings = Setting::pluck('value', 'key');
            
            $admin_modules = null;
            if(auth()->check() && (auth()->user()->hasRole('SuperAdmin') || auth()->user()->hasRole('Admin')))
            {
                $admin_modules = Module::where('module_type', 'module')->orderBy('order','DESC')->get();
            }
            $view->with([
                // 'settings' => $settings,
                'admin_modules' => $admin_modules
            ]);
        });


        View::composer('layouts.components._header', function ($view) {
            $cart_model = new Cart;
            //get user/guest cart items sum
            $cart_items_sum = $cart_model->get_cart_items_qty('cart');
            //get user/guest wishlist items sum
            $wishlist_items_sum = $cart_model->get_cart_items_qty('wishlist');
            
            $view->with('cart_items_sum', $cart_items_sum);
            $view->with('wishlist_items_sum', $wishlist_items_sum);
        });

	}

	public function register()
	{
        if (Schema::hasTable('languages')) {
            $langs = DB::table('languages')->select('code', 'name', 'script', 'native', 'regional')->orderBy('order')->get()->keyBy('code');

            if(count($langs) > 0){
                $def_lang = $langs->first()->code;
                config(['app.locale' => $def_lang]);
            }
            $locales = [];
            foreach($langs as $key => $val){
                $locales[$key] = (array) $val;
            }
            // dd($locales);
            if(count($locales) > 0){
                config([
                    'laravellocalization.supportedLocales' => $locales,

                    'laravellocalization.useAcceptLanguageHeader' => true,

                    'laravellocalization.hideDefaultLocaleInURL' => true
                ]);
            }
        }


        $this->app->bind(
		    __DIR__.'\Interfaces\PageInterface',
		    __DIR__.'\Controllers\PageController'
		);
		//require __DIR__.'/../vendor/autoload.php';

		$this->app->bind(
		    "Codeman\Admin\Interfaces\PageInterface",
		    "Codeman\Admin\Services\PageService"
		);

//		$this->app->bind(
////		    "Codeman\Admin\Interfaces\NewsInterface",
////		    "Codeman\Admin\Services\NewsService"
////		);
		$this->app->bind(
		    "Codeman\Admin\Interfaces\MenuInterface",
		    "Codeman\Admin\Services\MenuService"
		);
		$this->app->bind(
		    "Codeman\Admin\Interfaces\ProductInterface",
		    "Codeman\Admin\Services\ProductService"
		);

		$this->app->register(\Collective\Html\HtmlServiceProvider::class);
        $this->app->register(\Proengsoft\JsValidation\JsValidationServiceProvider::class);
        $this->app->register(\Mcamara\LaravelLocalization\LaravelLocalizationServiceProvider::class);
		$this->app->register(\Laravolt\Avatar\ServiceProvider::class);
		$this->app->register(\Barryvdh\TranslationManager\ManagerServiceProvider::class);
		$this->app->register(Menu\MenuServiceProvider::class);

		$this->setEnvironmentValue(['ANALYTICS_VIEW_ID' => '185059691']);

	}




	public function setEnvironmentValue(array $values)
	{

//		$envFile = app()->environmentFilePath();
//		$str = file_get_contents($envFile);
//
//		if (count($values) > 0) {
//			foreach ($values as $envKey => $envValue) {
//
//				$str .= "\n"; // In case the searched variable is in the last line without \n
//				$keyPosition = strpos($str, "{$envKey}=");
//				$endOfLinePosition = strpos($str, "\n", $keyPosition);
//				$oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
//
//				// If key does not exist, add it
//				if (!$keyPosition || !$endOfLinePosition || !$oldLine) {
//					$str .= "{$envKey}={$envValue}\n";
//				} else {
//					//$str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
//				}
//
//			}
//		}
//
//		$str = substr($str, 0, -1);
//		if (!file_put_contents($envFile, $str)) return false;
//		return true;

	}
}

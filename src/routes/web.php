<?php
// dd(auth()->check());
use Spatie\Sitemap\SitemapGenerator;

Route::middleware('web')->group(function () {

	Route::namespace('Codeman\Admin\Http\Controllers')->group(function () {
		// Authentication Routes...
		Route::get('admin/login', 'Auth\LoginController@showLoginForm')->name('login');
		Route::post('admin/login', 'Auth\LoginController@login')->name('postLogin');


		// Registration Routes...
		// Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
		// Route::post('register', 'Auth\RegisterController@register');

		// Password Reset Routes...
		Route::get('admin/password/email', 'Auth\ForgotPasswordController@showLinkRequestForm');
		Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail');
		Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.request');
		Route::post('password/reset', 'Auth\ResetPasswordController@reset');
	});
// dd(auth()->check());
Route::middleware(['auth', 'admin'])->group(function () {

	Route::get('/clear-cache', function() {
	    $exitCode = Artisan::call('cache:clear');
	});

	Route::get('/queue-work', function() {
	    Artisan::call('queue:work');
	});


	// Route::get('admin/logout', 'Auth\LoginController@postLogout');

	Route::namespace('Codeman\Admin\Http\Controllers')->group(function () {

		Route::get('/admin/logout', 'Auth\LoginController@postLogout')->name('admin-logout');

		Route::get('admin/plural/{name}', function($name){
			return str_plural(strtolower($name));
		});


		// Route::get('admin/countries-json', function(){
		// 	return json_encode(array_values(config('countries')));
		// });

		// DashboardController
		Route::get('admin/dashboard', 'DashboardController@index')->name('dashboard');

		//change featured image durig resource list
		Route::post('admin/change-resource-featured-image', 'Controller@changeFeaturedImage');

		//search resource
		Route::get('admin/resource/search', 'Controller@searchResource');

		//search resource
		Route::get('admin/resource/filter', 'Controller@filterResource');

		//search resource
        Route::post('admin/resource/bulk-delete', 'Controller@bulkDeleteResource');

		Route::post('admin/resource/update-order', 'Controller@updateOrder');

		// Route::post('/admin/resource/get-film-some-data', 'Controller@getFilmSomeData');
		// Route::post('/admin/resource/get-current-year-film-names', 'Controller@getCurrentYearFilmNames');
		//Product Categories List
		Route::get('/admin/resource/resource-categories-names/{type}', 'Controller@getResourceCategoriesAndNames');




        Route::get('generatesitemap', function () {
            SitemapGenerator::create(env('APP_URL'))->writeToFile('sitemap.xml');
            return redirect()->back();
        })->name('sitemap.generate');

		// Route::get('admin/joomag', 'DashboardController@api');

		// CategoriesController
		Route::get('admin/homepage/index', 'HomeController@index')->name('admin-home');
		// Route::post('admin/homepage/update', 'HomeController@update');
		// Route::post('admin/homepage/update-settings', 'HomeController@updateSettings');

		// CategoriesController
		// Route::get('admin/categories', 'CategoriesController@index');
		Route::get('admin/categories/create/{type?}', 'CategoriesController@create')->name('categories.create');
		Route::post('admin/categories/store', 'CategoriesController@store')->name('categories.store');
		Route::get('admin/categories/edit/{id}/{type?}', 'CategoriesController@edit')->name('categories.edit');
		Route::put('admin/categories/update/{id}', 'CategoriesController@update')->name('categories.update');
		Route::get('admin/categories/destroy/{id}', 'CategoriesController@destroy')->name('categories.destroy');
//		Route::get('admin/categories/add-language/{id}', 'CategoriesController@addLanguage')->name('category-addLanguage');
        Route::get('admin/categories/translate/{id}/{lang}', 'CategoriesController@translate')->name('categories.translate');
        Route::get('admin/categories/{type}/{lang}/{parent?}', 'CategoriesController@categories_by_lang')->name('categories.categories_by_lang');

		// PagesController
		Route::get('admin/pages', 'PagesController@index')->name('page-index');
		Route::get('admin/pages/create/{lang?}', 'PagesController@create')->name('page-create');
		Route::post('admin/pages/store', 'PagesController@store')->name('page-store');
		Route::get('admin/pages/edit/{id}', 'PagesController@edit')->name('page-edit');
		Route::put('admin/pages/update/{id}', 'PagesController@update')->name('page-update');
		Route::get('admin/pages/destroy/{id}', 'PagesController@destroy')->name('page-destroy');
		Route::get('admin/pages/translate/{id}/{lang}', 'PagesController@translate')->name('page-translate');
		Route::get('admin/pages/templates', 'PagesController@templates')->name('pages.templates');

        Route::resource('admin/modules', 'ModuleController');
        Route::get('admin/delete{id}', 'ModuleController@delete')->name('modules.delete');





		// resourceController
		Route::get('admin/resource/{resource}', 'ResourceController@index')->name('resources.index');
		Route::get('admin/resource/{resource}/create/{lang?}', 'ResourceController@create')->name('resources.create');
		Route::post('admin/resource/{resource}/store', 'ResourceController@store')->name('resources.store');
		Route::get('admin/resource/{resource}/edit/{id}', 'ResourceController@edit')->name('resources.edit');
		Route::put('admin/resource/{resource}/update/{id}', 'ResourceController@update')->name('resources.update');
		Route::get('admin/resource/{resource}/destroy/{id}', 'ResourceController@destroy')->name('resources.destroy');
		Route::get('admin/resource/{resource}/translate/{id}/{lang}', 'ResourceController@translate')->name('resources.translate');
		Route::get('admin/resource/{resource}/categories', 'ResourceController@categories')->name('resources.categories');



		// UsersController
		Route::get('admin/users', 'UserController@index')->name('user.index');
		Route::get('admin/users/create', 'UserController@create')->name('user.create');
		Route::post('admin/users/store', 'UserController@store')->name('user.store');
		Route::get('admin/users/edit/{id}', 'UserController@edit')->name('user.edit');
		Route::put('admin/users/update/{id}', 'UserController@update')->name('user.update');
		Route::get('admin/users/destroy/{id}', 'UserController@destroy')->name('user.destroy');


		//RolesController
		Route::get('admin/roles', 'RoleController@index')->name('roles.index');
		Route::get('admin/roles/create', 'RoleController@create')->name('roles.create');
		Route::post('admin/roles/store', 'RoleController@store')->name('roles.store');
		Route::get('admin/roles/edit/{id}', 'RoleController@edit')->name('roles.edit');
		Route::put('admin/roles/update/{id}', 'RoleController@update')->name('roles.update');
		Route::get('admin/roles/destroy/{id}', 'RoleController@destroy')->name('roles.destroy');


		// PagesController->name('product-')
		Route::get('admin/custom-pages/team', 'CustomPagesController@team');
		Route::get('admin/custom-pages/team/create', 'CustomPagesController@teamCreate');
		Route::post('admin/custom-pages/team/store', 'CustomPagesController@teamStore');
		Route::get('admin/custom-pages/team/edit/{id}', 'CustomPagesController@teamEdit');
		Route::put('admin/custom-pages/team/update/{id}', 'CustomPagesController@teamUpdate');
		Route::get('admin/custom-pages/team/translate/{id}', 'CustomPagesController@teamTranslate');
		Route::get('admin/custom-pages/team/destroy/{id}', 'CustomPagesController@teamDestroy');

		Route::get('admin/custom-pages/contact-us', 'CustomPagesController@contactUs');
		Route::get('admin/custom-pages/categories', 'CustomPagesController@categories');


		// MenusController
		Route::get('admin/menus', 'MenusController@index')->name('menu-index');
		Route::get('admin/menus/create', 'MenusController@create')->name('menu-create');
		Route::post('admin/menus/store', 'MenusController@store')->name('menu-store');
		Route::get('admin/menus/show/{id}', 'MenusController@show')->name('menu-show');
		Route::put('admin/menus/update/{id}', 'MenusController@update')->name('menu-update');
		Route::get('admin/menus/translate/{id}/{lnag}', '\Codeman\Admin\Menu\Controllers\MenuController@translate')->name('menu-translate');
		Route::get('admin/menus/destroy/{id}', 'MenusController@destroy')->name('menu-destroy');



		// Settings Controller
		Route::get('admin/settings', 'SettingsController@index')->name('setting.index');
		Route::get('admin/settings/{type}/{index}', 'SettingsController@type')->name('setting.type');
		Route::post('admin/settings/update', 'SettingsController@createOrUpdate')->name('setting.update');





		// Application Controller
		Route::get('admin/applications', 'ApplicationController@index')->name('applications-index');
		Route::get('admin/applications/destroy/{id}', 'ApplicationController@destroy')->name('application-destroy');


		// // GalleriesController
		// Route::get('admin/galleries/all/{year?}', 'GalleriesController@index');
		// Route::get('admin/galleries/create', 'GalleriesController@create');
		// Route::post('admin/galleries/store', 'GalleriesController@store');
		// // Route::get('admin/galleries/show/{id}', 'GalleriesController@show');
		// Route::get('admin/galleries/year/{year}', 'GalleriesController@selectGalleriesByYear');
		// Route::get('admin/galleries/id/{id}', 'GalleriesController@selectGalleryById');
		// // Route::get('admin/galleries/filter', 'GalleriesController@filter');


		// Route::post('admin/galleries/storeimages', 'GalleriesController@storeImages');

		// Route::get('admin/galleries/edit/{id}', 'GalleriesController@edit');
		// Route::put('admin/galleries/update/{id}', 'GalleriesController@update');
		// // Route::get('admin/daily/add-language/{id}', 'NewsController@addLanguage');
		// Route::get('admin/galleries/destroy/{id}', 'GalleriesController@destroy');
        Route::resource('admin/product', '\App\Http\Controllers\ProductController');
        Route::resource('admin/vendor', '\App\Http\Controllers\VendorController');
        Route::resource('admin/offer', '\App\Http\Controllers\OfferController');
        Route::get('admin/product/delete/{id}', '\App\Http\Controllers\ProductController@destroy')->name('product.delete');
        Route::get('admin/vendor/delete/{id}', '\App\Http\Controllers\VendorController@destroy')->name('vendor.delete');
        Route::get('admin/offer/delete/{id}', '\App\Http\Controllers\OfferController@destroy')->name('offer.delete');
        Route::get('admin/vendor/translate/{id}/{lang}', '\App\Http\Controllers\VendorController@translate')->name('vendor.translate');
        Route::get('admin/product/translate/{id}/{lang}', '\App\Http\Controllers\ProductController@translate')->name('product.translate');
        Route::get('admin/offer/translate/{id}/{lang}', '\App\Http\Controllers\OfferController@translate')->name('offer.translate');
        Route::get('admin/offer/products/{vendor}', '\App\Http\Controllers\OfferController@products');
        Route::get('admin/products-categories', '\App\Http\Controllers\ProductController@categories')->name('product.categories');



        //ImagesController
		Route::get('admin/media','ImagesController@index')->name('image-index');
		Route::get('admin/media/json','ImagesController@getAllImagesJson');
		Route::get('admin/media/popup','ImagesController@popup');
		Route::get('admin/media/upload','ImagesController@upload')->name('image-upload');
		Route::post('admin/media/upload', 'ImagesController@postUpload');
		Route::put('admin/media/update', 'ImagesController@update');
		Route::get('/admin/media/search', 'ImagesController@search');
		Route::post('/admin/media/delete', 'ImagesController@delete');


	});
});


});

<?php
// dd(auth()->check());
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


		// ProgramController
//		Route::get('admin/programs', 'ProgramController@index')->name('program-index');
//		Route::get('admin/programs/create', 'ProgramController@create')->name('program-create');
//		Route::post('admin/programs/store', 'ProgramController@store')->name('program-store');
//		Route::get('admin/programs/edit/{id}', 'ProgramController@edit')->name('program-edit');
//		Route::put('admin/programs/update/{id}', 'ProgramController@update')->name('program-update');
//		Route::get('admin/programs/destroy/{id}', 'ProgramController@destroy')->name('program-destroy');
//		Route::get('admin/programs/translate/{id}/{lang}', 'ProgramController@translate')->name('program-translate');
//		Route::get('admin/programs/categories', 'ProgramController@categories')->name('program-categories');


		// resourceController
		Route::get('admin/resource/{resource}', 'ResourceController@index')->name('resources.index');
		Route::get('admin/resource/{resource}/create/{lang?}', 'ResourceController@create')->name('resources.create');
		Route::post('admin/resource/{resource}/store', 'ResourceController@store')->name('resources.store');
		Route::get('admin/resource/{resource}/edit/{id}', 'ResourceController@edit')->name('resources.edit');
		Route::put('admin/resource/{resource}/update/{id}', 'ResourceController@update')->name('resources.update');
		Route::get('admin/resource/{resource}/destroy/{id}', 'ResourceController@destroy')->name('resources.destroy');
		Route::get('admin/resource/{resource}/translate/{id}/{lang}', 'ResourceController@translate')->name('resources.translate');
		Route::get('admin/resource/{resource}/categories', 'ResourceController@categories')->name('resources.categories');


        // fileController
        Route::get('admin/file', 'FileController@index')->name('file-index');
        Route::get('admin/file/create', 'FileController@create')->name('file-create');
        Route::post('admin/file/store', 'FileController@store')->name('file-store');
        Route::get('admin/file/edit/{id}', 'FileController@edit')->name('file-edit');
        Route::put('admin/file/update/{id}', 'FileController@update')->name('file-update');
        Route::get('admin/file/destroy/{id}', 'FileController@destroy')->name('file-destroy');
        Route::get('admin/file/translate/{id}/{lang}', 'FileController@translate')->name('file-translate');
        Route::get('admin/file/categories', 'FileController@categories')->name('file-categories');

		// ReviewController
		Route::get('admin/reviews', 'ReviewController@index')->name('reviews-index');
		Route::get('admin/review/create', 'ReviewController@create')->name('review-create');
		Route::post('admin/reviews/store', 'ReviewController@store')->name('review-store');
		Route::get('admin/reviews/edit/{id}', 'ReviewController@edit')->name('review-edit');
		Route::put('admin/reviews/update/{id}', 'ReviewController@update')->name('review-update');
		Route::get('admin/reviews/destroy/{id}', 'ReviewController@destroy')->name('review-destroy');
		Route::get('admin/reviews/translate/{id}/{lang}', 'ReviewController@translate')->name('review-translate');

		// ServiceController
		Route::get('admin/services', 'ServiceController@index')->name('service-index');
		Route::get('admin/services/create', 'ServiceController@create')->name('service-create');
		Route::post('admin/services/store', 'ServiceController@store')->name('service-store');
		Route::get('admin/services/edit/{id}', 'ServiceController@edit')->name('service-edit');
		Route::put('admin/services/update/{id}', 'ServiceController@update')->name('service-update');
		Route::get('admin/services/destroy/{id}', 'ServiceController@destroy')->name('service-destroy');
		Route::get('admin/services/translate/{id}/{lang}', 'ServiceController@translate')->name('service-translate');

		// FeedProductController
		Route::get('admin/feed-product', 'FeedProductController@index')->name('feed-product-index');
		Route::get('admin/feed-product/{id}', 'FeedProductController@show')->name('feed-product-show');
		Route::get('admin/feed-product/destroy/{id}', 'FeedProductController@destroy')->name('feed-product-destroy');
		Route::post('admin/feed-product/status', 'FeedProductController@changeStatus')->name('feed-product-status');
		Route::get('admin/feed-product/edit/{id}', 'FeedProductController@edit')->name('feed-product-edit');
		Route::put('admin/feed-product/update/{id}', 'FeedProductController@update')->name('feed-product-update');
		Route::get('admin/feed-categories', 'FeedProductController@categories')->name('feed-product-categories');


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
		Route::get('admin/menus/translate/{id}', 'MenusController@translate')->name('menu-translate');
		Route::get('admin/menus/destroy/{id}', 'MenusController@destroy')->name('menu-destroy');

		// NewsController
		Route::get('admin/news', 'NewsController@index')->name('news-index');
		Route::get('admin/news/create', 'NewsController@create')->name('news-create');
		Route::post('admin/news/store', 'NewsController@store')->name('news-store');
		// Route::get('almanac/{slug}', 'NewsController@show')->name('news-show');
		Route::get('admin/news/edit/{id}', 'NewsController@edit')->name('news-edit');
		Route::put('admin/news/update/{id}', 'NewsController@update')->name('news-update');
		Route::get('admin/news/translate/{id}', 'NewsController@translate')->name('news-translate');
		Route::get('admin/news/destroy/{id}', 'NewsController@destroy')->name('news-destroy');
		Route::get('admin/news/categories', 'NewsController@categories')->name('news-categories');

		// Settings Controller
		Route::get('admin/settings', 'SettingsController@index')->name('setting.index');
		Route::post('admin/settings/update', 'SettingsController@createOrUpdate')->name('setting.update');


		// Lecturers Controller
		Route::get('admin/lecturers', 'LecturerController@index')->name('lecturer-index');
		Route::get('admin/lecturers/create', 'LecturerController@create')->name('lecturer-create');
		Route::post('admin/lecturers/store', 'LecturerController@store')->name('lecturer-store');
		// Route::get('almanac/{slug}', 'LecturerController@show')->name('lecturers-show');
		Route::get('admin/lecturer/edit/{id}', 'LecturerController@edit')->name('lecturer-edit');
		Route::put('admin/lecturer/update/{id}', 'LecturerController@update')->name('lecturer-update');
		Route::get('admin/lecturer/translate/{id}/{lang}', 'LecturerController@translate')->name('lecturer-translate');
		Route::get('admin/lecturer/destroy/{id}', 'LecturerController@destroy')->name('lecturer-destroy');
		Route::get('admin/lecturer/categories', 'LecturerController@categories')->name('lecturer-categories');
		

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

		//ImagesController
		Route::get('admin/media','ImagesController@index')->name('image-index');
		Route::get('admin/media/json','ImagesController@getAllImagesJson');
		Route::get('admin/media/popup','ImagesController@popup');
		Route::get('admin/media/upload','ImagesController@upload')->name('image-upload');
		Route::post('admin/media/upload', 'ImagesController@postUpload');
		Route::put('admin/media/update', 'ImagesController@update');
		Route::get('/admin/media/search', 'ImagesController@search');
		Route::post('/admin/media/delete', 'ImagesController@delete');


		// 	//SubscribersController
			Route::get('admin/subscribers', 'SubscribersController@index')->name('subscriber-index');
			Route::get('admin/subscribers/create', 'SubscribersController@create')->name('subscriber-create');
			Route::post('admin/subscribers/store', 'SubscribersController@store')->name('subscriber-store');
			Route::get('admin/subscribers/destroy/{id}', 'SubscribersController@destroy')->name('subscriber-destroy');

		// 	Route::get('admin/settings', 'SettingsController@index')->name('setting-index');
		// 	Route::post('admin/settings/create-or-update', 'SettingsController@createOrUpdate');

	});
});

	
});

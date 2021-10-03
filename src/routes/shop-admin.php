Route::group(['middleware' => ['admin']], function() {
    
    Route::get('time', function(){
        return \Carbon\Carbon::now()->format('d-m-Y H:i:s');
    });
    
	Route::resource('admin/products', '\Codeman\Admin\Http\Controllers\Shop\AdminProductsController');
    Route::get('admin/products-categories', '\Codeman\Admin\Http\Controllers\Shop\AdminProductsController@categories')->name('products.categories');
    Route::get('admin/products/translate/{id}/{lang}', '\Codeman\Admin\Http\Controllers\Shop\AdminProductsController@translate')->name('products.translate');

	Route::resource('admin/product-options', '\Codeman\Admin\Http\Controllers\Shop\AdminProductOptionsController');
    Route::get('admin/product-options/delete/{id}', '\Codeman\Admin\Http\Controllers\Shop\AdminProductOptionsController@deleteOption');
    Route::get('admin/product-options/translate/{id}/{lang}', '\Codeman\Admin\Http\Controllers\Shop\AdminProductOptionsController@translate')->name('product-options.translate');

    Route::get('admin/product-options/{id}/options', '\Codeman\Admin\Http\Controllers\Shop\AdminProductOptionsController@getOptionsOfGroup');
    Route::get('admin/variations/delete/{id}', '\Codeman\Admin\Http\Controllers\Shop\AdminVariationsController@destroy');
    Route::get('admin/variations-generate/{id}/{type}', '\Codeman\Admin\Http\Controllers\Shop\AdminVariationsController@generateVariations');
	
	Route::resource('admin/brands', '\Codeman\Admin\Http\Controllers\Shop\AdminBrandsController');
    Route::get('admin/brands/translate/{id}/{lang}', '\Codeman\Admin\Http\Controllers\Shop\AdminBrandsController@translate')->name('brands.translate');
    // Route::get('admin/brands/categories', 'BrandsController@categories')->name('resources.categories');

    // Route::get('/xml', '\Codeman\Admin\Http\Controllers\Shop\AdminSmartSystemController@xml');

    Route::get('admin/orders', '\Codeman\Admin\Http\Controllers\Shop\AdminOrdersController@index')->name('admin.orders');
    Route::get('admin/orders/login-as-user/{user_id}', '\Codeman\Admin\Http\Controllers\Shop\AdminOrdersController@loginAsUser')->name('admin.orders.user.login');
    Route::get('admin/orders/show/{id}', '\Codeman\Admin\Http\Controllers\Shop\AdminOrdersController@show')->name('admin.orders.show');
    Route::post('admin/orders/update-status/{id}', '\Codeman\Admin\Http\Controllers\Shop\AdminOrdersController@update_status')->name('admin.order.update.status');

    Route::get('admin/orders/transaction-status/{payment_id}', 'Payments\AmeriaBankPaymentController@checkPaymentStatus')->name('admin.order.transaction.status');

});
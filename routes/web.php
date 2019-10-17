<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
if (App::environment('production')) {
    URL::forceScheme('https');
}

Auth::routes();

Route::post('send_feedback', 'MailController@feedback');  

Route::group(['prefix' => '', 'middleware' => ['locale']], function () {       
    Route::get('', 'HomeController@index');
    
    Route::group(['prefix' => '{locale}'], function () {       
        Route::get('', 'HomeController@index');    
        
        /* Static Pages */
        Route::get('{slug}', 'HomeController@staticPageShow')->where(['slug'=>'terms|revocation']);
                
        Route::get('services', 'HomeController@servicesIndex');  
        Route::get('services/{slug}', 'HomeController@serviceShow');  
        
        Route::get('blog', 'HomeController@blogIndex');  
        Route::get('blog/category', 'HomeController@blogIndex');  
        Route::get('blog/category/{slug}', 'HomeController@blogIndex');
        Route::get('blog/{slug}', 'HomeController@blogShow');  
        
        Route::get('cars', 'HomeController@autosIndex');  
        Route::get('cars_filter', 'HomeController@autosAjax');  
        Route::get('cars/{id}', 'HomeController@autosShow');        
        
        Route::get('caravan_rent', 'HomeController@rentIndex');  
        Route::get('caravan_rent/{id}', 'HomeController@rentIndex');  
                
        Route::get('cart', 'HomeController@cart');  
        Route::post('cart_update', 'HomeController@cartUpdate');  
        Route::post('order_create', 'HomeController@orderCreate');
        Route::get('confirmation', 'HomeController@orderConfirmation');  
        
        Route::get('contacts', 'HomeController@contacts');  
        
        Route::any('{any}', 'HomeController@show404')->where(['any'=>'.*']);
    });
    
});

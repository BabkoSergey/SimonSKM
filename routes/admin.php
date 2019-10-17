<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "admin" middleware group. Now create something great!
|
*/
if (App::environment('production')) {
    URL::forceScheme('https');
}

Auth::routes(['verify' => true, 'register' => false]);

Route::group(['prefix' => 'files', 'middleware' => ['auth', 'admin'], 'namespace' => 'Files'], function () {
        
    Route::get('get_all', ['as'=>'image.list.get','uses'=>'ImageController@getUploadImgs']);
    Route::post('upload', ['as'=>'image.upload.post','uses'=>'ImageController@imageUploadPost']);
    Route::post('uploads', ['as'=>'image.upload.multi.post','uses'=>'ImageController@imageUploadMultiPost']);
    Route::post('remove', ['as'=>'image.remove.post','uses'=>'ImageController@imageDeletePost']);

});

Route::group(['prefix' => '', 'middleware' => ['auth', 'admin'], 'namespace' => 'Admin'], function () {
    
    /*
     * Dashboard
     */
    Route::get('','DashboardController@index');
    Route::get('dashboard','DashboardController@index');    
    
    /*
     * Services
     */
    Route::resource('services','ServiceController');        
    Route::get('services_dt_ajax', 'ServiceController@servicesDTAjax');
    Route::get('/services/{ids}/ban','ServiceController@ban');
    
    /*
     * Pages
     */
    Route::resource('pages','PageController');  
    
    /*
     * Autos
     */
    Route::resource('cars','AutoController');        
    Route::get('cars_dt_ajax', 'AutoController@autosDTAjax');
    Route::get('cars_remove_logo', 'AutoController@autosRemoveLogo');
    Route::get('/cars/{ids}/ban','AutoController@ban');
    
    /*
     * Orders
     */
    Route::resource('orders','OrderController');        
    Route::get('orders_dt_ajax', 'OrderController@ordersDTAjax');
    Route::get('/orders/{ids}/users','OrderController@usersOredersGet');
    
    /*
     * Trailers
     */    
    Route::resource('trailers','TrailerController');        
    Route::get('trailers/disables/{trailer}/create', 'TrailerController@disablesCreate');
    Route::get('trailers/disables/{trailer}/{disables}/edit', 'TrailerController@disablesEdit');
    Route::post('trailers/disables/add/{trailer}', 'TrailerController@disablesStore');
    Route::post('trailers/disables/{trailer}/{disables}/update','TrailerController@disablesUpdate');
    Route::post('trailers/disables/{trailer}/{disables}/delete','TrailerController@disablesDelete');    
    
    /*
     * Articles & ArtCategorys
     */
    Route::resource('articles','ArticleController');        
    Route::get('articles_dt_ajax', 'ArticleController@articlesDTAjax');
    Route::get('/articles/{ids}/ban','ArticleController@ban');
    
    Route::resource('art_categorys','ArtCategoryController');        
    Route::get('art_categorys_dt_ajax', 'ArtCategoryController@art_categotysDTAjax');
    Route::get('/art_categorys/{ids}/ban','ArtCategoryController@ban');
    
    /*
     * Users
     */
    Route::resource('users','UserController');    
    Route::post('/users/{id}/update_info','UserController@updateInfo');
    Route::get('users_dt_ajax', 'UserController@usersDTAjax');
    Route::get('/users/{ids}/ban','UserController@ban');
       
    /*
     * Permissions
     */
    Route::resource('permissions','PermissionController');
    Route::get('permissions_dt_ajax', 'PermissionController@permissionsDTAjax');    
    
    /*
     * Roles
     */
    Route::resource('roles','RoleController');
    Route::get('roles_dt_ajax', 'RoleController@rolesDTAjax');        
    
    /*
     * Settings Block
     */
    Route::group(['prefix' => 'settings'], function () {
        
        /*
        * Translate
        */
        Route::get('translate', ['as'=>'settings.translate.index','uses'=>'TranslateController@index']);
        Route::post('translations/update', 'TranslateController@transUpdate')->name('translation.update.json');
        Route::post('translations/updateKey', 'TranslateController@transUpdateKey')->name('translation.update.json.key');
        Route::delete('translations/destroy/{key}', 'TranslateController@destroy')->name('translations.destroy');
        Route::post('translations/create', 'TranslateController@store')->name('translations.create');
        
        /*
        * Settings
        */
        Route::resource('general','SettingController');
        
    });
            
});

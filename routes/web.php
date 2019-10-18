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
Route::group(['middleware' => 'auth'], function (){
    Route::get('/', 'UserController@home');
    Route::post('/orders', 'OrderController@index');
    Route::get('/orders', 'OrderController@index');
    Route::get('/orders_list', 'OrderController@ordersList');
    Route::resource('/user', 'UserController');
    Route::get('/errors', 'ErrorsController@index');
    Route::delete('/errors/{id}', 'ErrorsController@destroy');
    Route::delete('/errors_massive_delete', 'ErrorsController@massiveDestroy');
    Route::get('/set_shippment', 'ShippmentController@index');
    Route::post('/save_shippments', 'ShippmentController@save');
});


Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

Auth::routes(['register' => false]);

Route::get('/home', 'HomeController@index')->name('home');

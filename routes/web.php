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

Route::get('/', function () {
    return view('home');
});
Route::get('/orders', 'OrderController@index');

Route::resource('/user', 'UserController');
Route::get('/errors', 'ErrorsController@index');
Route::delete('/errors/{id}', 'ErrorsController@destroy');

Route::get('/set_shippment', 'ShippmentController@index');
Route::post('/save_shippments', 'ShippmentController@save');

Route::group(['prefix' => 'admin'], function () {
    Voyager::routes();
});

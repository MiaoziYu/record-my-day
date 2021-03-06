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

Auth::routes();
Route::get('/', 'Web\HomeController@index');

Route::group(['namespace' => 'Auth'], function () {
    Route::get('/register', 'RegisterController@create');
    Route::post('/register', 'RegisterController@store');
    Route::get('/login', 'LoginController@create');
    Route::post('/login', 'LoginController@store');
    Route::get('/logout', 'LoginController@destroy');
});

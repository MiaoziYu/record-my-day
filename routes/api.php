<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::group(['namespace' => 'Api'], function () {
    Route::get('/records/', 'RecordsController@index');
    Route::post('/records/', 'RecordsController@store');
    Route::put('/records/', 'RecordsController@update');
    Route::delete('/records/', 'RecordsController@destroy');
    Route::get('/records/{id}', 'RecordsController@show');
});

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

Route::prefix('values')->group(function() {
    Route::get('/', 'ValueController@index');
    Route::post('/', 'ValueController@store');
    Route::patch('/', 'ValueController@update');
});

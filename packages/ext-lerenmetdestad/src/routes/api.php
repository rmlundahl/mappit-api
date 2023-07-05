<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


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

// routes without locale
Route::group(['prefix' => 'api/v1'], function() {

    Route::get('/lerenmetdestad/import/hello-world',       '\Mappit\ExtLerenMetDeStad\Http\Controllers\API\ImportController@helloWorld');
    Route::get('/lerenmetdestad/import/excel', '\Mappit\ExtLerenMetDeStad\Http\Controllers\API\ImportController@import_excel');

});

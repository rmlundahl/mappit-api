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

\DB::connection()->enableQueryLog();

// e.g. /api/v1/en/items
$locale = request()->segment(3);

if(!array_key_exists($locale, config('mappit.supported_locales'))) {
    $locale = 'nl';
}

App::setLocale($locale);

Route::group(['prefix' => 'v1/'.$locale], function() {
    
    /**
    * User Routes
    */
    Route::get('/user', 'API\UsersController@show');
    
    // Item routes
    Route::get('/items',         'API\ItemController@index');
    Route::get('/items/{id}',    'API\ItemController@find');
    Route::post('/items',        'API\ItemController@store');
    Route::put('/items/{id}',    'API\ItemController@update');
    Route::delete('/items/{id}', 'API\ItemController@delete');

});

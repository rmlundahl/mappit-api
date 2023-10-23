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

// // e.g. /api/v1/en/items
$locale = request()->segment(3);

if(!array_key_exists($locale, config('mappit.supported_locales'))) {
    $locale = 'nl';
}

App::setLocale($locale);

// routes without locale
Route::group(['prefix' => 'v1'], function() {

    // User routes
    Route::get('/user',          'API\UsersController@show');
    Route::get('/users',         'API\UsersController@index');
    Route::get('/users_from_group/{group_id}', 'API\UsersController@users_from_group');
    Route::get('/users/{id}',    'API\UsersController@find');
    Route::post('/users',        'API\UsersController@store');
    Route::put('/users/{id}',    'API\UsersController@update');
    Route::delete('/users/{id}', 'API\UsersController@delete');

    // Group routes
    Route::get('/groups_from_user', 'API\GroupController@groups_from_user');

    // Image routes
    Route::get ('/images/{path}', 'API\ImageController@index')->where('path', '[\w\s\-_\/]+');
    Route::post('/images',        'API\ImageController@store');

    // Notification routes
    Route::get('/notifications',         'API\NotificationController@index');
    Route::get('/notifications/{id}',    'API\NotificationController@find');
    Route::put('/notifications/{id}',    'API\NotificationController@update');
    Route::delete('/notifications/{id}', 'API\NotificationController@delete');
});

// routes with locale
Route::group(['middleware'=>'setLocale', 'prefix' => 'v1/'.$locale], function() {
    
    // Item routes
    Route::get('/items/all_markers',   'API\ItemController@all_markers');
    Route::get('/items/all_from_user', 'API\ItemController@all_from_user');
    Route::get('/items',               'API\ItemController@index');
    Route::get('/items/{id}',          'API\ItemController@find');
    Route::post('/items',              'API\ItemController@store');
    Route::put('/items/{id}',          'API\ItemController@update');
    Route::delete('/items/{id}',       'API\ItemController@delete');

    // Filter routes
    Route::get('/filters',             'API\FilterController@index');
    Route::get('/filters/{id}',        'API\FilterController@find');

    // Collection routes
    Route::get('/collections',         'API\CollectionController@index');
    Route::get('/collections/{id}',    'API\CollectionController@find');
    
});

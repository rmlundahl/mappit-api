<?php

// e.g. /nl/forgot-password
$locale = request()->segment(1);

if(!array_key_exists($locale, config('mappit.supported_locales'))) {
    $locale = 'nl';
}

App::setLocale($locale);


Route::get('/','HomeController@index')->name('home');

// For development purposes only
Route::get('/dev', 'DevController@dev');

/**
* Authentication Routes
*/
Route::post('/register', 'Auth\AuthController@register');
Route::post('/login', 'Auth\AuthController@login');
Route::post('/logout', 'Auth\AuthController@logout');

Route::group(['prefix' => $locale], function() {
    Route::post('/forgot-password', 'Auth\PasswordResetLinkController@store');
    Route::get ('/reset-password/{token}', 'Auth\NewPasswordController@create')->name('password.reset');
    Route::post('/reset-password', 'Auth\NewPasswordController@store')->name('password.update');
});

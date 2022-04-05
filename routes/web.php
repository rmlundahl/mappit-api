<?php

Route::get('/','HomeController@index')->name('home');

// For development purposes only
Route::get('/groups', 'DevController@groups');

/**
* Authentication Routes
*/
Route::post('/register', 'Auth\AuthController@register');
Route::post('/login', 'Auth\AuthController@login');
Route::post('/logout', 'Auth\AuthController@logout');
Route::post('/forgot-password', 'Auth\PasswordResetLinkController@store');

// Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->middleware('guest')->name('password.reset');

<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use Laravel\Fortify\Features;
use Laravel\Fortify\Http\Controllers\AuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\ConfirmablePasswordController;
use Laravel\Fortify\Http\Controllers\ConfirmedPasswordStatusController;
use Laravel\Fortify\Http\Controllers\ConfirmedTwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationNotificationController;
use Laravel\Fortify\Http\Controllers\EmailVerificationPromptController;
use Laravel\Fortify\Http\Controllers\NewPasswordController;
use Laravel\Fortify\Http\Controllers\PasswordController;
use Laravel\Fortify\Http\Controllers\PasswordResetLinkController;
use Laravel\Fortify\Http\Controllers\ProfileInformationController;
use Laravel\Fortify\Http\Controllers\RecoveryCodeController;
use Laravel\Fortify\Http\Controllers\RegisteredUserController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticatedSessionController;
use Laravel\Fortify\Http\Controllers\TwoFactorAuthenticationController;
use Laravel\Fortify\Http\Controllers\TwoFactorQrCodeController;
use Laravel\Fortify\Http\Controllers\TwoFactorSecretKeyController;
use Laravel\Fortify\Http\Controllers\VerifyEmailController;
use Laravel\Fortify\RoutePath;

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
    Route::post('/users/import', 'API\UsersImportController@import');


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

    
    // Password Confirmation...
    Route::get(RoutePath::for('password.confirmation', '/user/confirmed-password-status'), [ConfirmedPasswordStatusController::class, 'show'])
        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        ->name('password.confirmation');

    Route::post(RoutePath::for('password.confirm', '/user/confirm-password'), [ConfirmablePasswordController::class, 'store'])
        ->middleware([config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')])
        ->name('password.confirm');

        
    // Two Factor Authentication...
    if (Features::enabled(Features::twoFactorAuthentication())) {
        
        $limiter = config('fortify.limiters.login');
        $twoFactorLimiter = config('fortify.limiters.two-factor');
        $verificationLimiter = config('fortify.limiters.verification', '6,1');

        Route::post(RoutePath::for('login', '/login'), [AuthenticatedSessionController::class, 'store'])
        ->middleware(array_filter([
            'guest:'.config('fortify.guard'),
            $limiter ? 'throttle:'.$limiter : null,
        ]));

        Route::post(RoutePath::for('two-factor.login', '/two-factor-challenge'), [TwoFactorAuthenticatedSessionController::class, 'store'])
            ->middleware(array_filter([
                'guest:'.config('fortify.guard'),
                $twoFactorLimiter ? 'throttle:'.$twoFactorLimiter : null,
            ]));

        $twoFactorMiddleware = Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')
            ? [config('fortify.auth_middleware', 'auth').':'.config('fortify.guard'), 'password.confirm']
            : [config('fortify.auth_middleware', 'auth').':'.config('fortify.guard')];

        Route::post(RoutePath::for('two-factor.enable', '/user/two-factor-authentication'), [TwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.enable');

        Route::post(RoutePath::for('two-factor.confirm', '/user/confirmed-two-factor-authentication'), [ConfirmedTwoFactorAuthenticationController::class, 'store'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.confirm');

        Route::delete(RoutePath::for('two-factor.disable', '/user/two-factor-authentication'), [TwoFactorAuthenticationController::class, 'destroy'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.disable');

        Route::get(RoutePath::for('two-factor.qr-code', '/user/two-factor-qr-code'), [TwoFactorQrCodeController::class, 'show'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.qr-code');

        Route::get(RoutePath::for('two-factor.secret-key', '/user/two-factor-secret-key'), [TwoFactorSecretKeyController::class, 'show'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.secret-key');

        Route::get(RoutePath::for('two-factor.recovery-codes', '/user/two-factor-recovery-codes'), [RecoveryCodeController::class, 'index'])
            ->middleware($twoFactorMiddleware)
            ->name('two-factor.recovery-codes');

        Route::post(RoutePath::for('two-factor.recovery-codes', '/user/two-factor-recovery-codes'), [RecoveryCodeController::class, 'store'])
            ->middleware($twoFactorMiddleware);
    }
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

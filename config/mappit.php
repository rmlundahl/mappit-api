<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Supported locales
	|--------------------------------------------------------------------------
	|
	| An array with locales that are supported by this Mappit application
	| The current locale will be derived from the first segment in the url
	| e.g. for https://mappit.com/nl/home the current locale will be 'nl'
	|
	*/
	'supported_locales' => [
		'en' => 'en_US.UTF-8',
		'es' => 'es_ES.UTF-8',
		'nl' => 'nl_NL.UTF-8',
	],
	
	/*
    |--------------------------------------------------------------------------
    | URL frontend application
    |--------------------------------------------------------------------------
    |
    | Define the base url for the frontend application where this mappit 
    | instance serves as backend for
    |
    */
	'app_url_frontend' => env('APP_URL_FRONTEND')

];

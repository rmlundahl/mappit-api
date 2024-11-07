<?php

namespace Mappit\ExtShareMyStory;

use Illuminate\Support\ServiceProvider;

use App;

class ExtShareMyStoryServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Only register on the right domain
        if( stripos(config('app.url'), 'sharemystory') === false && App::environment() != 'testing') {
            return;
        }
        $this->loadTranslationsFrom(__DIR__ . '/resources/lang', 'sharemystory');
    }
}

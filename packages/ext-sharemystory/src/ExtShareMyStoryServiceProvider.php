<?php

namespace Mappit\ExtShareMyStory;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Console\Scheduling\Schedule;
use Mappit\ExtHvaindestad\Console\Commands\ImportJson;

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

<?php

namespace Mappit\ExtLerenMetDeStad;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Console\Scheduling\Schedule;
use Mappit\ExtHvaindestad\Console\Commands\ImportJson;

use App;

class ExtLerenMetDeStadServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Only register on the right domain
        if( stripos(config('app.url'), 'lerenmetdestad') === false && App::environment() != 'testing') {
            return;
        }
        
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');

    }
}

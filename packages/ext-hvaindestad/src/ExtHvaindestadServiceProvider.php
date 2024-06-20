<?php

namespace Mappit\ExtHvaindestad;

use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Console\Scheduling\Schedule;
use Mappit\ExtHvaindestad\Console\Commands\ImportJson;

use App;

class ExtHvaindestadServiceProvider extends ServiceProvider
{
    public function register()
    {
        //
    }

    public function boot()
    {
        // Only register on the right domain
        if( stripos(config('app.url'), 'hvaindestad') === false && App::environment() != 'testing') {
            return;
        }
        $this->mergeConfigFrom(__DIR__.'/config/config.php', 'exthvaindestad');
        $this->configureRateLimiting();
        $this->loadRoutesFrom(__DIR__.'/routes/api.php');
        
        // Register the command if we are using the application via the CLI
        if ($this->app->runningInConsole()) {
            $this->commands([
                ImportJson::class,
            ]);
        }

        /**
         * Salesforce will be retired at HvA. Stop the daily importing 
         */

        // Schedule the command if we are using the application via the CLI
        // if ($this->app->runningInConsole()) {
        //     $this->app->booted(function () {
        //         $schedule = $this->app->make(Schedule::class);
        //         $schedule->command('exthvaindestad:import_json')->dailyAt('01:15');
        //     });
        // }
    }

    /**
     * Configure the rate limiters for the application.
     *
     * @return void
     */
    protected function configureRateLimiting()
    {
        RateLimiter::for('api', function (Request $request) {
            return Limit::perMinute(60)->by(optional($request->user())->id ?: $request->ip());
        });
    }
}

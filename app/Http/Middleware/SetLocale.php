<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // e.g. /api/v1/en/items
        $locale = $request->segment(3);

        if(!array_key_exists((string) $locale, config('mappit.supported_locales'))) {
            $locale = 'nl';
        }

        app()->setLocale((string) $locale);

        return $next($request);
    }
}

<?php

namespace Larasoft\LaravelRemote\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Exceptions\MaintenanceModeException;
use \Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode as CheckForMaintenance;

class LaravelRemoteCheckForMaintenanceMode extends CheckForMaintenance
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        // Exclude "php artisan up" from maintenance middleware to run command successfully.

        if(!str_is('/laravel-remote*', $request->getRequestUri()))
        {
            if ( $this->app->isDownForMaintenance() )
            {
                $data = json_decode(file_get_contents($this->app->storagePath() . '/framework/down'), true);

                throw new MaintenanceModeException($data['time'], $data['retry'], $data['message']);
            }
        }
        
        return $next($request);
    }
}

<?php

namespace Larasoft\LaravelRemote;

use Illuminate\Support\ServiceProvider;

class LaravelRemoteServiceProvider extends ServiceProvider
{
    /**
     * Perform post-registration booting of services.
     *
     * @return void
     */
    public function boot()
    {
        include __DIR__.'/Http/routes.php';

        $this->publishes([
                             __DIR__ . '/config/remote.php' => config_path('remote.php'),
                         ]);

        $this->publishes([
                             __DIR__ . '/Http/Middleware/LaravelRemoteCheckForMaintenanceMode.php' => app_path('Http/Middleware/LaravelRemoteCheckForMaintenanceMode.php'),
                         ]);


        include __DIR__.'/Http/Middleware/LaravelRemoteCheckForMaintenanceMode.php';
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/config/remote.php', 'remote'
        );
    }
}
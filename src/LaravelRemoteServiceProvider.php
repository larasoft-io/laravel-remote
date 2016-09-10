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
        $this->publishes([
            __DIR__.'/config/remote.php' => config_path('remote.php'),
        ]);

        $this->publishes([
            __DIR__ . '/Http/Middleware/LaravelRemoteCheckForMaintenanceMode.php' => app_path('Http/Middleware'),
        ]);

        include __DIR__.'/Http/routes.php';

        include __DIR__.'/Http/Middleware/LaravelRemoteCheckForMaintenanceMode.php';
    }

    /**
     * Register any package services.
     *
     * @return void
     */
    public function register()
    {
//        $this->publishes([
//            __DIR__ . '/config/remote.php' => config_path('remote.php'),
//        ]);
//
//        $this->publishes([
//             __DIR__ . '/Http/Middleware/LaravelRemoteCheckForMaintenanceMode.php' => app_path('Http/Middleware'),
//         ]);

        $this->mergeConfigFrom(
            __DIR__.'/config/remote.php', config_path('remote.php')
        );
    }
}
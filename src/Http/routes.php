<?php

    // Test the connection with Laravel Remote
    Route::get(
        'laravel-remote/chstatus',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@getStatus'
    );

    Route::get(
        'laravel-remote/env/variables',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@getEnvVariables'
    );

    // Execute commands received from Laravel Remote
    Route::get(
        'laravel-remote/{command}',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@executeCommand'
    );


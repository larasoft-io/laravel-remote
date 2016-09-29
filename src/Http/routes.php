<?php

    // Test the connection with Laravel Remote
    Route::get(
        'laravel-remote/status/check',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@getStatus'
    );

    Route::get(
        'laravel-remote/env/variables',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@getEnvVariables'
    );

    Route::post(
        'laravel-remote/env/variable',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@updateEnvVariable'
    );

    // Execute commands received from Laravel Remote
    Route::get(
        'laravel-remote/{command}',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@executeCommand'
    );


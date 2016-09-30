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
        'laravel-remote/update/env/variable',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@updateEnvVariable'
    );

    Route::post(
        'laravel-remote/store/env/variable',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@storeEnvVariable'
    );

    Route::post(
        'laravel-remote/delete/env/variable',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@deleteEnvVariable'
    );

    // Execute commands received from Laravel Remote
    Route::get(
        'laravel-remote/{command}',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@executeCommand'
    );


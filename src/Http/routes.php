<?php

    // Test the connection with Laravel Remote
    Route::get(
        'laravel-remote/status/check',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@getStatus'
    );

    // Execute commands received from Laravel Remote
    Route::get(
        'laravel-remote/{command}',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@executeCommand'
    );


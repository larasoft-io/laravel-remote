<?php

    // Test the connection with Laravel Remote
    Route::get(
        'laravel-remote/connection/test',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@testConnection'
    );

    // Execute commands received from Laravel Remote
    Route::get(
        'laravel-remote/{command}',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@executeCommand'
    );


<?php

    Route::get(
        'laravel-remote/version',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@getVersion'
    );

    Route::get(
        'laravel-remote/jobs',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@getJobNames'
    );

    // Test the connection with Laravel Remote
    Route::get(
        'laravel-remote/connection/check',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@checkConnection'
    );

    Route::get(
        'laravel-remote/env/raw',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@getRawEnvFile'
    );

    Route::post(
        'laravel-remote/env/raw',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@saveRawEnvFile'
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

    Route::get(
        'laravel-remote/backups',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@backups'
    );

    Route::get(
        'laravel-remote/backups/{name}/download',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@downloadBackup'
    );

    Route::post(
        'laravel-remote/backups/{name}/delete',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@deleteBackup'
    );

    // Execute commands received from Laravel Remote
    Route::get(
        'laravel-remote/{command}',
        '\Larasoft\LaravelRemote\Http\Controllers\LaravelRemoteController@executeCommand'
    );


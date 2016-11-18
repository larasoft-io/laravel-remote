# Laravel Remote

[![Latest Version on Packagist][ico-version]][link-packagist]
[![Software License][ico-license]](LICENSE.md)
[![Build Status][ico-travis]][link-travis]
[![Coverage Status][ico-scrutinizer]][link-scrutinizer]
[![Quality Score][ico-code-quality]][link-code-quality]
[![Total Downloads][ico-downloads]][link-downloads]

Laravel Remote supports Laravel 5.* currently.

## Install

Via Composer

``` bash
$ composer require larasoft/laravel-remote dev-master
```

Add following two providers in providers array of "config/app.php".

``` php
'providers' => [
    ...
    \Larasoft\LaravelRemote\LaravelRemoteServiceProvider::class,
    Spatie\Backup\BackupServiceProvider::class,
    
    ];
```

Run "php artisan vendor:publish" in project root to publish config files and middleware.

## Configure

### Step 1 (Required)
In "app/Http/Kernel.php, replace "\Illuminate\Foundation\Http\Middleware\CheckForMaintenanceMode::class" with "LaravelRemoteCheckForMaintenanceMode::class" in "$middleware" array.

In "config/remote.php", replace 'LARAVEL_REMOTE_KEY' with your generated key in Laravel Remote Dashboard.
``` php
return [
    'key' => env('LARAVEL_REMOTE_KEY'),
    'url' => env('LARAVEL_REMOTE_URL')
];
```

### Step 2 (Required for Database Backups feature)

Configure your config/database.php as follows to enable Backups of your database.

```
'connections' => [
	'mysql' => [
		'dump_command_path' => '/path/to/the/binary', // only the path, so without 'mysqldump' or 'pg_dump'
		'dump_command_timeout' => 60 * 5, // 5 minute timeout
		'dump_using_single_transaction' => true, // perform dump using a single transaction
		'driver'    => 'mysql',
		...
	],
```
For more information regarding Database backups visit: https://docs.spatie.be/laravel-backup/v3/introduction

### Step 3 (Required for Failed Job Notification feature)

In "config/remote.php", replace 'LARAVEL_REMOTE_URL' with base URL of Laravel Remote Dashboard (without trailing /). e.g: http://laravel-remote.com
``` php
return [
    'key' => env('LARAVEL_REMOTE_KEY'),
    'url' => env('LARAVEL_REMOTE_URL')
];
```

Add following entry in $listen array of App\Providers\EventServiceProvider.
``` php
protected $listen = [
    LaravelRemoteJobFailed::class => [
        ListenLaravelRemoteJobFailed::class,
    ],
    ...
];
```

Override failed() method in your job classes i.e. in app/Jobs directory as follows.
``` php
    public function failed()
    {
        $data = ['job' => class_basename($this)];
        event(new LaravelRemoteJobFailed($data));
        
        ...
    }
```


That's it.

## Usage

Use Laravel Remote Dashboard to manage your Apps. Enjoy!

## Change log

Please see [CHANGELOG](CHANGELOG.md) for more information what has changed recently.

## Testing

``` bash
$ composer test
```

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) and [CONDUCT](CONDUCT.md) for details.

## Security

If you discover any security related issues, please email :author_email instead of using the issue tracker.

## Credits

- [:author_name][link-author]
- [All Contributors][link-contributors]

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

[ico-version]: https://img.shields.io/packagist/v/:vendor/:package_name.svg?style=flat-square
[ico-license]: https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square
[ico-travis]: https://img.shields.io/travis/:vendor/:package_name/master.svg?style=flat-square
[ico-scrutinizer]: https://img.shields.io/scrutinizer/coverage/g/:vendor/:package_name.svg?style=flat-square
[ico-code-quality]: https://img.shields.io/scrutinizer/g/:vendor/:package_name.svg?style=flat-square
[ico-downloads]: https://img.shields.io/packagist/dt/:vendor/:package_name.svg?style=flat-square

[link-packagist]: https://packagist.org/packages/:vendor/:package_name
[link-travis]: https://travis-ci.org/:vendor/:package_name
[link-scrutinizer]: https://scrutinizer-ci.com/g/:vendor/:package_name/code-structure
[link-code-quality]: https://scrutinizer-ci.com/g/:vendor/:package_name
[link-downloads]: https://packagist.org/packages/:vendor/:package_name
[link-author]: https://github.com/:author_username
[link-contributors]: ../../contributors

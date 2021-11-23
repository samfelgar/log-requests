# Log requests

This package provides a simple request logger to be used with Laravel applications.

## Installation

You can install this package via composer:

```shell
composer require samfelgar/log-requests
```

By default, the logging channel used is the `stack`, but you can change this by publishing the config file:

```shell
php artisan vendor:publish --provider=Samfelgar\\LogRequests\\Providers\\LogRequestServiceProvider
```

After running the command above, the file `log-requests.php` will be created in the config path.

## Usage

This package registers a middleware alias that can be used in your routes files:

```php
use Illuminate\Support\Facades\Route;

Route::middleware(['log-requests'])->group(function () {
    // Your logged routes goes here.
});
```

You may also register the middleware within a group or globally by editing the `\App\Http\Kernel` class:

- In groups

```php
protected $middlewareGroups = [
    'web' => [
        // Other middleware
        \Samfelgar\LogRequests\Http\Middleware\LogRequest::class,
    ],

    'api' => [
        'throttle:api',
        'bindings',
        \Samfelgar\LogRequests\Http\Middleware\LogRequest::class,
    ],
];
```

- Globally

```php
protected $middleware = [
    // Other middleware
    \Samfelgar\LogRequests\Http\Middleware\LogRequest::class,
];
```

> For more information about middlewares in Laravel, check the [documentation](https://laravel.com/docs/middleware#registering-middleware).

## Contributing

Found an error? Open an issue!

Any contribution is appreciated, just submit a pull request.
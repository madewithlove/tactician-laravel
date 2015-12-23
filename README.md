# madewithlove/tactician-laravel

[![Build Status](http://img.shields.io/travis/madewithlove/tactician-laravel.svg?style=flat-square)](https://travis-ci.org/madewithlove/tactician-laravel)
[![Latest Stable Version](http://img.shields.io/packagist/v/madewithlove/tactician-laravel.svg?style=flat-square)](https://packagist.org/packages/madewithlove/tactician-laravel)
[![Total Downloads](http://img.shields.io/packagist/dt/arrounded/madewithlove/tactician-laravel?style=flat-square)](https://packagist.org/packages/madewithlove/tactician-laravel)
[![Scrutinizer Quality Score](http://img.shields.io/scrutinizer/g/madewithlove/tactician-laravel.svg?style=flat-square)](https://scrutinizer-ci.com/g/madewithlove/tactician-laravel)
[![Code Coverage](http://img.shields.io/scrutinizer/coverage/g/madewithlove/tactician-laravel.svg?style=flat-square)](https://scrutinizer-ci.com/g/madewithlove/tactician-laravel)

## Introduction

This packages is a replacement for Laravel 5's default command bus using [tactician](http://tactician.thephpleague.com).

### Default middleware

- [LockingMiddleware](http://tactician.thephpleague.com/plugins/locking-middleware/) (block commands from running inside commands)
- TransactionMiddleware (Run all commands in a database transaction and rolls back incase of failure)

### Command Handling

By default commands will be resolved as followed:

```
Acme\Jobs\Foo => Acme\Listeners\Foo
Acme\Foo\Jobs\Bar => Acme\Foo\Listeners\Bar
```

All command handlers are resolved out of the [container](http://laravel.com/docs/5.2/container) which mean you can use all kind of Laravel goodies.

## Install

``` bash
$ composer require madewithlove/tactician-laravel
```

Add the service provider to `config/app.php`:

```php
Madewithlove\Tactician\ServiceProvider::class,
```

In case you want to tweak the middlewares you should publish the package configuration:

```php
php artisan vendor:publish --provider="Madewithlove\Tactician\ServiceProvider"
```

## Usage

### Writing commands

A command always consists out of two parts: the command and the handler.

```php
// Products\Jobs\CalculatePriceForQuantity
class CalculatePriceForQuantity
{
    public $price;

    public $amount;

    public function __construct($price, $amount = 1)
    {
        $this->price = $price;
        $this->amount = $amount;
    }
}

use Products\Jobs\CalculatePriceForQuantity as Job;

// Products\Listeners\CalculatePriceForQuantity
class CalculatePriceForQuantity
{
    public function handle(Job $job)
    {
        return $job->amount * $job->price;
    }
}
```

#### Overriding the command handling logic

If you're not happy with the default logic shipped in this package you can overwrite it easily by
rebinding the  `League\Tactician\Handler\CommandHandlerMiddleware`. You do this by adding the following to your
application's service provider, refer to [Tactician's documentation](http://tactician.thephpleague.com/tweaking-tactician/)
for options.

```php
public function register()
{
    $this->app->bind(CommandHandlerMiddleware::class, function () {
        // Return your own implementation of CommandHandlerMiddleware here.
    });
}
```

## Middlewares

This package includes a couple middlewares specific to Laravel, you can choose to use these.

### `TransactionMiddleware`

This middleware is included by default. It means all your commands are handled inside of a database transaction, and
if an error occurs it will rollback the transaction.

It's quite common for a command to throw an exception that is caught higher up the chain so a certain action can be performed
(such as displaying an error message) but still perform some kind of database interaction. For cases such as this you can make use of
the `Madewithlove\Tactician\Contracts\IgnoresRollback` interface. Simply implement it on your exception and no rollbacks will be performed!

__Note__: This middleware only runs the main database connection in a transaction, if you use multiple connections you will need
to come up with a custom solution.

## Testing

``` bash
$ composer test
```

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

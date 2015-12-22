<?php

use League\Tactician\Plugins\LockingMiddleware;
use League\Tactician\Handler\CommandHandlerMiddleware;
use Madewithlove\Tactician\Middlewares\TransactionMiddleware;

return [
    /*
    |--------------------------------------------------------------------------
    | Middlewares
    |--------------------------------------------------------------------------
    |
    | The middlewares to inject into your command bus, you can simply pass the class name
    | and the middleware will be resolved from the IoC Container.
    |
    */

    'middlewares' => [
        LockingMiddleware::class,
        TransactionMiddleware::class,
        CommandHandlerMiddleware::class,
    ],
];

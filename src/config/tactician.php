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

    /*
    |--------------------------------------------------------------------------
    | Replacements
    |--------------------------------------------------------------------------
    |
    | The ContainerLocator provided by this package will match your commands
    | to your handlers by replacing part of the command namespace.
    |
    | Use this config to customize what to look in the Command namespace and
    | what to replace it with.
    |
    */

    'replacements' => [
        'origin' => 'Jobs',
        'target' => 'Listeners',
    ]
];

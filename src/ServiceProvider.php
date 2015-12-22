<?php

namespace Madewithlove\Tactician;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(CommandBus::class, function () {
            $middlewares = [];

            $middlewares[] = new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                new ContainerLocator($this->app),
                new HandleInflector()
            );

            return new CommandBus($middlewares);
        });

        $this->app->alias(CommandBus::class, 'bus');
    }

    /**
     * @return array
     */
    public function provides()
    {
        return [
            CommandBus::class,
            'bus',
        ];
    }
}

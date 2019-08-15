<?php

namespace Madewithlove\Tactician;

use League\Tactician\CommandBus;
use League\Tactician\Handler\CommandHandlerMiddleware;
use League\Tactician\Handler\CommandNameExtractor\ClassNameExtractor;
use League\Tactician\Handler\MethodNameInflector\HandleInflector;

class ServiceProvider extends \Illuminate\Support\ServiceProvider
{
    /**
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     */
    public function register(): void
    {
        $configPath = __DIR__.'/config/tactician.php';
        $this->mergeConfigFrom($configPath, 'tactician');

        $this->app->bind(CommandHandlerMiddleware::class, function () {
            /** @var \Illuminate\Contracts\Config\Repository $config */
            $config = $this->app['config'];

            return new CommandHandlerMiddleware(
                new ClassNameExtractor(),
                new ContainerLocator(
                    $this->app,
                    $config->get('tactician.replacements.origin', 'Jobs'),
                    $config->get('tactician.replacements.target', 'Listeners')
                ),
                new HandleInflector()
            );
        });

        $this->app->bind(CommandBus::class, function () {
            $middlewares = array_map(function ($depencency) {
                if (is_string($depencency)) {
                    return $this->app->make($depencency);
                }

                return $depencency;
            }, config('tactician.middlewares', []));

            return new CommandBus($middlewares);
        });

        $this->app->alias(CommandBus::class, 'bus');
    }

    public function boot(): void
    {
        $this->publishes([
            __DIR__.'/config/tactician.php' => config_path('tactician.php'),
        ]);
    }

    public function provides(): array
    {
        return [
            CommandHandlerMiddleware::class,
            CommandBus::class,
            'bus',
        ];
    }
}

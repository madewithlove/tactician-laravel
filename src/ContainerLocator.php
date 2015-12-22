<?php

namespace Madewithlove\Tactician;

use Illuminate\Contracts\Container\Container;
use League\Tactician\Exception\MissingHandlerException;
use League\Tactician\Handler\Locator\HandlerLocator;

class ContainerLocator implements HandlerLocator
{
    /**
     * @var Container
     */
    protected $container;

    /**
     * @param Container $container
     */
    public function __construct(Container $container)
    {
        $this->container = $container;
    }

    /**
     * @param string $commandName
     *
     * @return mixed
     */
    public function getHandlerForCommand($commandName)
    {
        $handlerName = str_replace('Jobs', 'Listeners', $commandName);

        if (!class_exists($handlerName)) {
            throw MissingHandlerException::forCommand($commandName);
        }

        return $this->container->make($handlerName);
    }
}

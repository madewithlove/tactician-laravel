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
     * @var string
     */
    private $origin;

    /**
     * @var string
     */
    private $target;

    /**
     * @param string    $origin
     * @param string    $target
     */
    public function __construct(Container $container, $origin = 'Jobs', $target = 'Listeners')
    {
        $this->container = $container;
        $this->origin = $origin;
        $this->target = $target;
    }

    /**
     * @param string $commandName
     */
    public function getHandlerForCommand($commandName)
    {
        $handlerName = str_replace($this->origin, $this->target, $commandName);

        if (!class_exists($handlerName)) {
            throw MissingHandlerException::forCommand($commandName);
        }

        return $this->container->make($handlerName);
    }
}

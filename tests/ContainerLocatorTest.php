<?php

namespace Madewithlove\Tactician;

use League\Tactician\Exception\MissingHandlerException;
use Madewithlove\Tactician\Dummies;
use Mockery;
use Illuminate\Container\Container;

class ContainerLocatorTest extends TestCase
{
    public function testCanTriggerExceptionWhenHandlerCannotBeFound()
    {
        $this->expectException(MissingHandlerException::class);
        $this->expectExceptionMessage('Missing handler for command Jobs\Bar');

        $locator = new ContainerLocator(new Container());
        $locator->getHandlerForCommand('Jobs\Bar');
    }

    public function testCanGetHandlerForCommandName()
    {
        $locator = new ContainerLocator(new Container());
        $handler = $locator->getHandlerForCommand('Madewithlove\Tactician\Dummies\Jobs\Foo');

        $this->assertInstanceOf(Dummies\Listeners\Foo::class, $handler);
    }

    public function testCanGetNestedHandlerForCommandName()
    {
        $locator = new ContainerLocator(new Container());
        $handler = $locator->getHandlerForCommand('Madewithlove\Tactician\Dummies\Nested\Jobs\Foo');

        $this->assertInstanceOf(Dummies\Nested\Listeners\Foo::class, $handler);
    }

    public function testCanHaveCustomOriginAndTargetFolders()
    {
        $locator = new ContainerLocator(new Container(), 'Commands', 'Handlers');
        $handler = $locator->getHandlerForCommand('Madewithlove\Tactician\Dummies\Commands\Foo');

        $this->assertInstanceOf(Dummies\Handlers\Foo::class, $handler);
    }
}

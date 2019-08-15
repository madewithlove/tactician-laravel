<?php

namespace Madewithlove\Tactician;

use Mockery;

abstract class TestCase extends \PHPUnit\Framework\TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }
}

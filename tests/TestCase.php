<?php

namespace Madewithlove\Tactician;

use PHPUnit_Framework_TestCase;
use Mockery;

abstract class TestCase extends PHPUnit_Framework_TestCase
{
    protected function tearDown()
    {
        Mockery::close();
    }
}

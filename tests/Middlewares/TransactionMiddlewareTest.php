<?php

namespace Madewithlove\Tactician\Middlewares;

use Illuminate\Database\DatabaseManager;
use Madewithlove\Tactician\Contracts\IgnoresRollback;
use Madewithlove\Tactician\TestCase;
use Mockery;
use Mockery\MockInterface;
use stdClass;
use Exception;

class IgnoredException extends Exception implements IgnoresRollback
{

}

class TransactionMiddlewareTest extends TestCase
{
    public function testCanCommitTransaction()
    {
        $database = Mockery::mock(DatabaseManager::class, function (MockInterface $mock) {
            $mock->shouldReceive('beginTransaction')->once();
            $mock->shouldReceive('commit')->once();
            $mock->shouldReceive('rollback')->never();
        });

        $middleware = new TransactionMiddleware($database);

        $executed = 0;
        $next = function () use (&$executed) {
            $executed++;
        };

        $middleware->execute(new stdClass(), $next);

        $this->assertEquals(1, $executed);
    }

    public function testCanRollbackTransaction()
    {
        $database = Mockery::mock(DatabaseManager::class, function (MockInterface $mock) {
            $mock->shouldReceive('beginTransaction')->once();
            $mock->shouldReceive('commit')->never();
            $mock->shouldReceive('rollback')->once();
        });

        $this->setExpectedException(Exception::class, 'command failed');

        $middleware = new TransactionMiddleware($database);

        $next = function () use (&$executed) {
            throw new Exception('command failed');
        };

        $middleware->execute(new stdClass(), $next);
    }

    public function testCanIgnoreRollback()
    {
        $database = Mockery::mock(DatabaseManager::class, function (MockInterface $mock) {
            $mock->shouldReceive('beginTransaction')->once();
            $mock->shouldReceive('commit')->once();
            $mock->shouldReceive('rollback')->never();
        });

        $this->setExpectedException(IgnoredException::class, 'command failed');

        $middleware = new TransactionMiddleware($database);

        $next = function () use (&$executed) {
            throw new IgnoredException('command failed');
        };

        $middleware->execute(new stdClass(), $next);
    }
}


<?php

namespace Madewithlove\Tactician\Middlewares;

use Exception;
use Illuminate\Database\DatabaseManager;
use League\Tactician\Middleware;
use Madewithlove\Tactician\Contracts\IgnoresRollback;

class TransactionMiddleware implements Middleware
{
    /**
     * @var DatabaseManager
     */
    protected $database;

    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    /**
     * @param object $command
     *
     * @throws Exception
     */
    public function execute($command, callable $next)
    {
        $this->database->beginTransaction();
        try {
            $returnValue = $next($command);
            $this->database->commit();
        } catch (Exception $exception) {
            if ($exception instanceof IgnoresRollback) {
                $this->database->commit();
            } else {
                $this->database->rollback();
            }

            throw $exception;
        }

        return $returnValue;
    }
}

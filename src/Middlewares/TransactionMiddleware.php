<?php

namespace Madewithlove\Tactician\Middlewares;

use League\Tactician\Middleware;
use Exception;
use Illuminate\Database\DatabaseManager;

class TransactionMiddleware implements Middleware
{
    /**
     * @var DatabaseManager
     */
    protected $database;

    /**
     * @param DatabaseManager $database
     */
    public function __construct(DatabaseManager $database)
    {
        $this->database = $database;
    }

    /**
     * @param object $command
     * @param callable $next
     *
     * @return mixed
     * @throws Exception
     */
    public function execute($command, callable $next)
    {
        $this->database->beginTransaction();
        try {
            $returnValue = $next($command);
        } catch (Exception $exception) {
            $this->database->rollback();
            throw $exception;
        }
        $this->database->commit();
        return $returnValue;
    }
}

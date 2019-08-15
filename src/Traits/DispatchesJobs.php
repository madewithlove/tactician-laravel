<?php

namespace Madewithlove\Tactician\Traits;

trait DispatchesJobs
{
    /**
     * @param $command
     */
    public function dispatch($command)
    {
        return app('bus')->handle($command);
    }
}

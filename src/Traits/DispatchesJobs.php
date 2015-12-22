<?php

namespace Madewithlove\Tactician\Traits;


trait DispatchesJobs
{
    /**
     * @param $command
     *
     * @return mixed
     */
    public function dispatch($command)
    {
        return app('bus')->handle($command);
    }
}

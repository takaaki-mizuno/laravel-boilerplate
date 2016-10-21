<?php

namespace App\Async\Workers;

class Worker
{
    /**
     * @param \App\Async\Jobs\Job $job
     *
     * @return bool
     */
    public function execute($job)
    {
        return true;
    }
}

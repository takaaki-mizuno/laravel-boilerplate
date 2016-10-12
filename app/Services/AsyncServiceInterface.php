<?php

namespace App\Services;

interface AsyncServiceInterface extends BaseServiceInterface
{
    /**
     * @param array $jobs
     *
     * @return \App\Async\Jobs\Job
     */
    public function getJob($jobs);

    /**
     * @param \App\Async\Jobs\Job $job
     *
     * @return \App\Async\Workers\Worker
     */
    public function getWorker($job);

    /**
     * @param array $jobObject
     */
    public function executeJob($jobObject);

    /**
     * @param int   $jobId
     * @param array $jobInfo
     */
    public function registerJob($jobId, $jobInfo);

    /**
     * @param int $userId
     */
    public function registerSignUpJob($userId);
}

<?php

namespace App\Services\Production;

use App\Async\Jobs\Job;
use App\Services\AsyncServiceInterface;
use Aws\Sqs\SqsClient;

class AsyncService extends BaseService implements AsyncServiceInterface
{
    public function getJob($jobs)
    {
        if (array_get($jobs, 'id', 0) == 0) {
            return;
        }

        return new Job($jobs);
    }

    public function getWorker($job)
    {
        switch ($job->id) {
            case Job::ID_REGISTER:
                return \App::make('\App\Async\Workers\UserRegistrationWorker');
        }

        return;
    }

    public function executeJob($jobObject)
    {
        $jobs = array_get($jobObject, 'jobs', []);
        foreach ($jobs as $jobArray) {
            $job = $this->getJob($jobArray);
            if (empty($job)) {
                continue;
            }
            $worker = $this->getWorker($job);
            if (empty($worker)) {
                continue;
            }
            $worker->execute($job);
        }
    }

    public function registerJob($jobId, $jobInfo)
    {
        $queueData = [
            'key' => config('async.key'),
            'jobs' => [
                [
                    'id' => $jobId,
                    'data' => $jobInfo,
                ],
            ],
        ];
        if (config('async.enable', false) == true) {
            $auth = [
                'key' => config('aws.accounts.key'),
                'secret' => config('aws.accounts.secret'),
                'region' => config('aws.accounts.region'),
            ];
            $sqs = new SqsClient($auth);
            $sqs->$sqs->sendMessage([
                'QueueUrl' => config('async.worker.queue'),
                'MessageBody' => json_encode($queueData),
            ]);
            // Log::info('Add Queue:' . json_encode($queueData));
        } else {
            $this->executeJob($queueData);
        }
    }

    public function registerSignUpJob($userId)
    {
        $this->registerJob(Job::ID_REGISTER, [
            'user_id' => $userId,
        ]);
    }
}

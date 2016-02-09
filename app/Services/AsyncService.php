<?php namespace App\Services;

use \App\Async\Jobs\Job;
use \Aws\Sqs\SqsClient;

class AsyncService
{

    public function getJob($jobs)
    {
        if (array_get($jobs, 'id', 0) == 0) {
            return null;
        }

        return new Job($jobs);
    }

    public function getWorker($job)
    {
        switch ($job->id) {
            case Job::ID_REGISTER:
                return \App::make('\App\Async\Workers\UserRegistrationWorker');
        }

        return null;
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
            'key'  => \Config::get('async.key'),
            'jobs' => [
                [
                    'id'   => $jobId,
                    'data' => $jobInfo,
                ],
            ],
        ];
        if (\Config::get('async.enable', false) == true) {
            $auth = [
                "key"    => \Config::get('aws.accounts.key'),
                "secret" => \Config::get('aws.accounts.secret'),
                "region" => \Config::get('aws.accounts.region'),
            ];
            $sqs = new SqsClient($auth);
            $sqs->$sqs->sendMessage([
                'QueueUrl'    => \Config::get('async.worker.queue'),
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

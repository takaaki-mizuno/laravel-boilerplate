<?php

namespace App\Async\Workers;

use App\Repositories\UserRepositoryInterface;
use App\Services\MailService;

class UserRegistrationWorker extends Worker
{
    /** @var \App\Repositories\UserRepositoryInterface */
    protected $userRepository;

    /** @var \App\Services\MailService */
    protected $mailService;

    public function __construct(
        UserRepositoryInterface $userRepository,
        MailService $mailService
    ) {
        $this->userRepository = $userRepository;
        $this->mailService = $mailService;
    }

    /**
     * @param \App\Async\Jobs\Job $job
     *
     * @return bool
     */
    public function execute($job)
    {
        $userId = $job->user_id;
        $user = $this->userRepository->find($userId);
        if (empty($user)) {
            return false;
        }
        $this->mailService->sendRegisteredMail($user);

        return true;
    }
}

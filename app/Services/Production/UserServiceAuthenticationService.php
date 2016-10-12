<?php

namespace App\Services\Production;

use App\Repositories\UserServiceAuthenticationRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserServiceAuthenticationServiceInterface;

class UserServiceAuthenticationService extends ServiceAuthenticationService implements UserServiceAuthenticationServiceInterface
{
    /** @var \App\Repositories\UserServiceAuthenticationRepositoryInterface */
    protected $serviceAuthenticationRepository;

    /** @var \App\Repositories\UserRepositoryInterface */
    protected $authenticatableRepository;

    public function __construct(
        UserRepositoryInterface $authenticatableRepository,
        UserServiceAuthenticationRepositoryInterface $serviceAuthenticationRepository
    ) {
        $this->authenticatableRepository = $authenticatableRepository;
        $this->serviceAuthenticationRepository = $serviceAuthenticationRepository;
    }
}

<?php namespace App\Services;

use App\Repositories\UserServiceAuthenticationRepositoryInterface;
use App\Repositories\UserRepositoryInterface;

class UserServiceAuthenticationService extends ServiceAuthenticationService
{

    /** @var \App\Repositories\UserServiceAuthenticationRepositoryInterface */
    protected $serviceAuthenticationRepository;

    /** @var \App\Repositories\UserRepositoryInterface */
    protected $authenticatableRepository;

    public function __construct(
        UserRepositoryInterface $authenticatableRepository,
        UserServiceAuthenticationRepositoryInterface $serviceAuthenticationRepository
    )
    {
        $this->authenticatableRepository = $authenticatableRepository;
        $this->serviceAuthenticationRepository = $serviceAuthenticationRepository;
    }

}
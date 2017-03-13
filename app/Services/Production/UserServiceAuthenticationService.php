<?php

namespace App\Services\Production;

use App\Repositories\UserServiceAuthenticationRepositoryInterface;
use App\Repositories\UserRepositoryInterface;
use App\Services\UserServiceAuthenticationServiceInterface;
use App\Repositories\ImageRepositoryInterface;

class UserServiceAuthenticationService extends ServiceAuthenticationService implements UserServiceAuthenticationServiceInterface
{
    /** @var \App\Repositories\UserServiceAuthenticationRepositoryInterface */
    protected $serviceAuthenticationRepository;

    /** @var \App\Repositories\UserRepositoryInterface */
    protected $authenticatableRepository;

    /** @var \App\Repositories\ImageRepositoryInterface */
    protected $imageRepository;

    public function __construct(
        UserRepositoryInterface                         $authenticatableRepository,
        UserServiceAuthenticationRepositoryInterface    $serviceAuthenticationRepository,
        ImageRepositoryInterface                        $imageRepository
    ) {
        $this->authenticatableRepository        = $authenticatableRepository;
        $this->serviceAuthenticationRepository  = $serviceAuthenticationRepository;
        $this->imageRepository                  = $imageRepository;
    }
}

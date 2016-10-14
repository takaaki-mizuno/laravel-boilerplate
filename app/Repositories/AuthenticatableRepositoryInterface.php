<?php

namespace App\Repositories;

use App\Models\AuthenticatableBase;

interface AuthenticatableRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @param string $email
     *
     * @return AuthenticatableBase|null
     */
    public function findByEmail($email);

    /**
     * @param string $facebookId
     *
     * @return AuthenticatableBase|null
     */
    public function findByFacebookId($facebookId);
}

<?php

namespace App\Services;

interface AuthenticatableServiceInterface extends BaseServiceInterface
{
    /**
     * @param int $id
     *
     * @return \App\Models\AuthenticatableBase
     */
    public function signInById($id);

    /**
     * @param array $input
     *
     * @return \App\Models\AuthenticatableBase
     */
    public function signIn($input);

    /**
     * @param array $input
     *
     * @return \App\Models\AuthenticatableBase
     */
    public function signUp($input);

    /**
     * @param string $email
     *
     * @return bool
     */
    public function sendPasswordReset($email);

    /**
     * @return bool
     */
    public function signOut();

    /**
     * @return bool
     */
    public function resignation();

    /**
     * @param \App\Models\AuthenticatableBase $user
     */
    public function setUser($user);

    /**
     * @return \App\Models\AuthenticatableBase
     */
    public function getUser();

    /**
     * @param string $email
     */
    public function sendPasswordResetEmail($email);

    /**
     * @param string $token
     *
     * @return null|\App\Models\AuthenticatableBase
     */
    public function getUserByPasswordResetToken($token);

    /**
     * @param string $email
     * @param string $password
     * @param string $token
     *
     * @return bool
     */
    public function resetPassword($email, $password, $token);

    /**
     * @return bool
     */
    public function isSignedIn();

    /**
     * @param  $input
     *
     * @return \App\Models\AuthenticatableBase
     */
    public function signInByAPI($input);

    /**
     * @param  $input
     *
     * @return \App\Models\AuthenticatableBase
     */
    public function signUpByAPI($input);

    /**
     * @param \App\Models\AuthenticatableBase $user
     *
     * @return \App\Models\AuthenticatableBase
     */
    public function setAPIAccessToken($user);

    /**
     * @return string
     */
    public function getGuardName();
}

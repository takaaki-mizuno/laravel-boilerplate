<?php

namespace App\Http\Controllers;

use App\Http\Requests\ForgotPasswordRequest;
use App\Http\Requests\ResetPasswordRequest;
use App\Services\AuthenticatableServiceInterface;

class PasswordController extends Controller
{
    /** @var \App\Services\AuthenticatableServiceInterface $authenticatableService */
    protected $authenticatableService;

    /** @var string $emailSetPageView */
    protected $emailSetPageView = '';

    /** @var string $passwordResetPageView */
    protected $passwordResetPageView = '';

    /** @var string $returnAction */
    protected $returnAction = '';

    public function __construct(AuthenticatableServiceInterface $authenticatableService)
    {
        $this->authenticatableService = $authenticatableService;
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function getForgotPassword()
    {
        return view($this->emailSetPageView, [
        ]);
    }

    /**
     * Send a reset link to the given user.
     *
     * @param \App\Http\Requests\ForgotPasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postForgotPassword(ForgotPasswordRequest $request)
    {
        $email = $request->get('email');
        $this->authenticatableService->sendPasswordResetEmail($email);

        return redirect()->back()->with('status', 'success');
    }

    /**
     * Display the password reset view for the given token.
     *
     * @param string $token
     *
     * @return \Illuminate\Http\Response
     */
    public function getResetPassword($token = null)
    {
        if (empty($token)) {
            abort(404);
        }

        $user = $this->authenticatableService->getUserByPasswordResetToken($token);
        if (empty($user)) {
            abort(404);
        }

        return view($this->passwordResetPageView, [
            'token' => $token,
        ]);
    }

    /**
     * Reset the given user's password.
     *
     * @param \App\Http\Requests\ResetPasswordRequest $request
     *
     * @return \Illuminate\Http\Response
     */
    public function postResetPassword(ResetPasswordRequest $request)
    {
        $email = $request->get('email');
        $token = $request->get('token');
        $password = $request->get('password');
        if ($password == $request->get('password_confirmation')) {
            if ($this->authenticatableService->resetPassword($email, $password, $token)) {
                return \Redirect::action($this->returnAction);
            }
        }

        return redirect()->back()->withInput($request->only('email'));
    }
}

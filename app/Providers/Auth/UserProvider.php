<?php namespace App\Providers\Auth;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Auth\EloquentUserProvider;

class UserProvider extends EloquentUserProvider
{

    public function __construct(HasherContract $hasher)
    {
        $this->model = '\App\Models\User';
        $this->hasher = $hasher;
    }

}

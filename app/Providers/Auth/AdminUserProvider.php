<?php namespace App\Providers\Auth;

use Illuminate\Contracts\Hashing\Hasher as HasherContract;
use Illuminate\Auth\EloquentUserProvider;

class AdminUserProvider extends EloquentUserProvider
{

    public function __construct(HasherContract $hasher)
    {
        $this->model = '\App\Models\AdminUser';
        $this->hasher = $hasher;
    }

}

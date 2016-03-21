<?php namespace App\Repositories\Eloquent;

use App\Repositories\PasswordResettableRepositoryInterface;
use Illuminate\Auth\Passwords\DatabaseTokenRepository;

class PasswordResettableRepository extends DatabaseTokenRepository implements PasswordResettableRepositoryInterface
{

    protected $tableName = "password_resets";

    protected $hashKey = "random";

    protected $expires = 60;

    protected function getDatabaseConnection()
    {
        return $connection = app()['db']->connection();
    }

    public function __construct()
    {
        parent::__construct($this->getDatabaseConnection(), $this->tableName, $this->hashKey,$this->expires);
    }

    public function findEmailByToken($token)
    {
        $token = (array) $this->getTable()->where('token', $token)->first();
        if ($this->tokenExpired($token) ) {
            return null;
        }

        return $token['email'];
    }

}

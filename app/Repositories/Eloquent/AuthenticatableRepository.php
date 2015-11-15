<?php namespace App\Repositories\Eloquent;

use App\Repositories\AuthenticatableRepositoryInterface;
use App\Models\AuthenticatableBase;

class AuthenticatableRepository extends SingleKeyModelRepository implements AuthenticatableRepositoryInterface
{

    public function getBlankModel()
    {
        return new AuthenticatableBase();
    }

    public function findByEmail($email)
    {
        $className = $this->getModelClassName();
        return $className::whereEmail($email)->first();
    }

    /**
     * @param \App\Models\AuthenticatableBase $model
     * @param array $input
     * @return \App\Models\Base
     */
    public function update($model, $input)
    {
        if (array_key_exists('password', $input)) {
            $model->setPassword($input['password']);
            unset($input['password']);
        }
        return parent::update($model, $input);
    }

}

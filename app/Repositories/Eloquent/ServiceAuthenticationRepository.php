<?php

namespace App\Repositories\Eloquent;

use App\Repositories\ServiceAuthenticationRepositoryInterface;
use App\Models\ServiceAuthenticationBase;

class ServiceAuthenticationRepository extends SingleKeyModelRepository implements ServiceAuthenticationRepositoryInterface
{
    public $authModelColumn = '';

    public function getAuthModelColumn()
    {
        return $this->authModelColumn;
    }

    public function getBlankModel()
    {
        return new ServiceAuthenticationBase();
    }

    public function rules()
    {
        return [
        ];
    }

    public function messages()
    {
        return [
        ];
    }

    public function findByServiceAndId($service, $id)
    {
        $class = $this->getModelClassName();

        return $class::whereService($service)->whereServiceId($id)->first();
    }

    public function findByServiceAndAuthModelId($service, $authModelId)
    {
        $class = $this->getModelClassName();

        return $class::whereService($service)->where("$this->authModelColumn", $authModelId)->first();
    }
}

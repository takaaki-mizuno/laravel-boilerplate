<?php namespace App\Services;

use App\Repositories\ServiceAuthenticationRepositoryInterface;
use App\Repositories\AuthenticatableRepositoryInterface;

class ServiceAuthenticationService
{

    /** @var \App\Repositories\ServiceAuthenticationRepositoryInterface */
    protected $serviceAuthenticationRepository;

    /** @var \App\Repositories\AuthenticatableRepositoryInterface */
    protected $authenticatableRepository;

    public function __construct(
        AuthenticatableRepositoryInterface $authenticatableRepository,
        ServiceAuthenticationRepositoryInterface $serviceAuthenticationRepository
    )
    {
        $this->authenticatableRepository = $authenticatableRepository;
        $this->serviceAuthenticationRepository = $serviceAuthenticationRepository;
    }

    /**
     * @param string $service
     * @param array $input
     * @return \App\Models\AuthenticatableBase
     */
    public function getAuthModelId($service, $input)
    {
        $columnName = $this->serviceAuthenticationRepository->getAuthModelColumn();

        $authInfo = $this->serviceAuthenticationRepository->findByServiceAndId($service,
            array_get($input, 'service_id'));
        if (!empty($authInfo)) {
            return $authInfo->$columnName;
        }

        $authUser = $this->authenticatableRepository->findByEmail(array_get($input, 'email'));
        if (!empty($authUser)) {
            $authInfo = $this->serviceAuthenticationRepository->findByServiceAndAuthModelId($service, $authUser->id);
            if (!empty($authInfo)) {
                return $authUser->id;
            }
        } else {
            $authUser = $this->authenticatableRepository->create($input);
        }

        $input[ $columnName ] = $authUser->id;
        $this->serviceAuthenticationRepository->create($input);

        return $authUser->id;
    }

}
<?php

namespace App\Services\Production;

use App\Repositories\ImageRepositoryInterface;
use App\Repositories\ServiceAuthenticationRepositoryInterface;
use App\Repositories\AuthenticatableRepositoryInterface;
use App\Services\ServiceAuthenticationServiceInterface;

class ServiceAuthenticationService extends BaseService implements ServiceAuthenticationServiceInterface
{
    /** @var \App\Repositories\ServiceAuthenticationRepositoryInterface */
    protected $serviceAuthenticationRepository;

    /** @var \App\Repositories\AuthenticatableRepositoryInterface */
    protected $authenticatableRepository;

    /** @var ImageRepositoryInterface $imageRepository */
    protected $imageRepository;

    public function __construct(
        AuthenticatableRepositoryInterface $authenticatableRepository,
        ServiceAuthenticationRepositoryInterface $serviceAuthenticationRepository,
        ImageRepositoryInterface $imageRepository
    ) {
        $this->authenticatableRepository = $authenticatableRepository;
        $this->serviceAuthenticationRepository = $serviceAuthenticationRepository;
        $this->imageRepository = $imageRepository;
    }

    /**
     * @param string $service
     * @param array  $input
     *
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
            if( array_key_exists('avatar', $input) ) {
                $image = $this->imageRepository->create([
                        'url'        => $input['avatar'],
                        'is_enabled' => true,
                    ]);
                $input['profile_image_id'] = $image->id;
            }
            $authUser = $this->authenticatableRepository->create($input);
        }

        $input[ $columnName ] = $authUser->id;
        $this->serviceAuthenticationRepository->create($input);

        return $authUser->id;
    }
}

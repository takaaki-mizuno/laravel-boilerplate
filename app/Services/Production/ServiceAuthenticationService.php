<?php

namespace App\Services\Production;

use App\Repositories\ServiceAuthenticationRepositoryInterface;
use App\Repositories\AuthenticatableRepositoryInterface;
use App\Services\ServiceAuthenticationServiceInterface;
use App\Repositories\ImageRepositoryInterface;

class ServiceAuthenticationService extends BaseService implements ServiceAuthenticationServiceInterface
{
    /** @var \App\Repositories\ServiceAuthenticationRepositoryInterface */
    protected $serviceAuthenticationRepository;

    /** @var \App\Repositories\AuthenticatableRepositoryInterface */
    protected $authenticatableRepository;

    public function __construct(
        AuthenticatableRepositoryInterface          $authenticatableRepository,
        ServiceAuthenticationRepositoryInterface    $serviceAuthenticationRepository
    ) {
        $this->authenticatableRepository        = $authenticatableRepository;
        $this->serviceAuthenticationRepository  = $serviceAuthenticationRepository;
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

        $authInfo = $this->serviceAuthenticationRepository->findByServiceAndServiceId($service, array_get($input, 'service_id'));
        if (!empty($authInfo)) {
            $authUser = $this->authenticatableRepository->find($authInfo->$columnName);
            if($input['avatar'] != '') {
                if ( isset($authUser->profileImage->url) ) {
                    $this->imageRepository->update(
                        $authUser->profileImage,
                        [
                            'url' => $input['avatar']
                        ]
                    );
                } else {
                    $image = $this->imageRepository->create(
                        [
                            'url'        => $input['avatar'],
                            'is_enabled' => true,
                        ]
                    );
                    $input['profile_image_id'] = $image->id;
                }
            }
            $this->serviceAuthenticationRepository->update($authUser, $input);

            return $authInfo->$columnName;
        }

        $authUser = $this->authenticatableRepository->findByEmail(array_get($input, 'email'));
        if (!empty($authUser)) {
            if($input['avatar'] != '') {
                if ( isset($authUser->profileImage->url) ) {
                    $this->imageRepository->update(
                        $authUser->profileImage,
                        [
                            'url' => $input['avatar']
                        ]
                    );
                } else {
                    $image = $this->imageRepository->create(
                        [
                            'url'        => $input['avatar'],
                            'is_enabled' => true,
                        ]
                    );
                    $input['profile_image_id'] = $image->id;
                }
            }
            $this->serviceAuthenticationRepository->update($authUser, $input);

            $authInfo = $this->serviceAuthenticationRepository->findByServiceAndAuthModelId($service, $authUser['id']);
            if (!empty($authInfo)) {
                $this->serviceAuthenticationRepository->update(
                    $authInfo,
                    [
                        'name'       => $input['name'],
                        'service_id' => $input['service_id']
                    ]
                );

                return $authUser->id;
            }
        } else {
            $image = $this->imageRepository->create(
                [
                    'url'        => $input['avatar'],
                    'is_enabled' => true,
                ]
            );
            $input['profile_image_id'] = $image->id;
            $authUser = $this->authenticatableRepository->create($input);
        }

        $input[$columnName] = $authUser->id;
        $this->serviceAuthenticationRepository->create($input);

        return $authUser->id;
    }
}

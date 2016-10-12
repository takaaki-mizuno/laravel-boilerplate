<?php

namespace App\Repositories;

interface ServiceAuthenticationRepositoryInterface extends SingleKeyModelRepositoryInterface
{
    /**
     * @return string
     */
    public function getAuthModelColumn();

    /**
     * Find Service Auth Info by service and service id.
     *
     * @param string $service
     * @param string $id
     *
     * @return \App\Models\ServiceAuthenticationBase
     */
    public function findByServiceAndId($service, $id);

    /**
     * Find Service Auth Info by service and user id.
     *
     * @param string $service
     * @param int    $authModelId
     *
     * @return \App\Models\ServiceAuthenticationBase
     */
    public function findByServiceAndAuthModelId($service, $authModelId);
}

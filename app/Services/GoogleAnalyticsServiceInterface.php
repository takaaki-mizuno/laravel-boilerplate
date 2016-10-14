<?php

namespace App\Services;

interface GoogleAnalyticsServiceInterface extends BaseServiceInterface
{
    /**
     * @return array
     */
    public function getProfileIds();

    /**
     * @param string $profileId
     *
     * @return array
     */
    public function getPageViews($profileId);
}

<?php namespace App\Services;

interface GoogleAnalyticsServiceInterface extends BaseServiceInterface
{

    /**
     * @return array
     */
    function getProfileIds();

    /**
     * @param  string $profileId
     * @return array
     */
    function getPageViews($profileId);

}
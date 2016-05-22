<?php namespace App\Services\Production;

use \App\Services\GoogleAnalyticsServiceInterface;

class GoogleAnalyticsService extends BaseService implements GoogleAnalyticsServiceInterface
{

    protected $service;

    public function __construct()
    {
        $this->service = null;
    }

    public function getService()
    {

        if (!empty( $this->service )) {
            return $this->service;
        }

        $client = new \Google_Client();
        $client->setApplicationName(\Config::get('google.app_name'));
        $analytics = new \Google_Service_Analytics($client);

        $key = file_get_contents(\Config::get('google.key_file_location'));
        $credential = new \Google_Auth_AssertionCredentials(\Config::get('google.service_account_email'),
            [\Google_Service_Analytics::ANALYTICS_READONLY], $key);
        $client->setAssertionCredentials($credential);
        if ($client->getAuth()->isAccessTokenExpired()) {
            $client->getAuth()->refreshTokenWithAssertion($credential);
        }

        $this->service = $analytics;

        return $this->service;
    }

    function getProfileIds()
    {
        $analytics = $this->getService();

        $accounts = $analytics->management_accounts->listManagementAccounts();

        $profileIds = [];

        foreach ($accounts->getItems() as $account) {

            $accountId = $account->getId();
            $properties = $analytics->management_webproperties->listManagementWebproperties($accountId);

            foreach ($properties->getItems() as $property) {

                $propertyId = $property->getId();

                $profiles = $analytics->management_profiles->listManagementProfiles($accountId, $propertyId);

                foreach ($profiles->getItems() as $profile) {
                    $profileIds[] = $profile->getId();

                }
            }
        }

        return $profileIds;
    }

    function getPageViews($profileId)
    {
        $analytics = $this->getService();

        $optParams = [
            'dimensions'  => 'ga:pagePath',
            'sort'        => '-ga:pageviews',
            'max-results' => '1000',
        ];

        $result = $analytics->data_ga->get('ga:'.$profileId, '2015-01-01', 'today',
            'ga:pageviews,ga:uniquePageviews,ga:timeOnPage,ga:bounces,ga:entrances,ga:exits', $optParams);
        $rows = $result->rows;
        $result = [];
        foreach ($rows as $row) {
            $result[ $row[0] ] = [
                'pv'         => $row[1],
                'uniquePv'   => $row[2],
                'timeOnPage' => $row[3],
                'bounce'     => $row[4],
                'entrance'   => $row[5],
                'exit'       => $row[6],
            ];
        }

        return $result;
    }

}

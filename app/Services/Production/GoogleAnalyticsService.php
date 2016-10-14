<?php

namespace App\Services\Production;

class GoogleAnalyticsService
{
    protected $service;

    public function __construct()
    {
        $this->service = null;
    }

    public function getService()
    {
        if (!empty($this->service)) {
            return $this->service;
        }

        $client = new \Google_Client();
        $client->setApplicationName(config('google.appName'));
        $client->setAuthConfig(config('google.keyFileLocation'));
        $client->setScopes([\Google_Service_Analytics::ANALYTICS_READONLY]);
        $analytics = new \Google_Service_Analytics($client);
        $this->service = $analytics;

        return $this->service;
    }

    public function getProfileIds()
    {
        $analytics = $this->getService();

        /** @var \Google_Service_Analytics_Accounts $accounts */
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

    public function getPageViews($profileId)
    {
        $analytics = $this->getService();

        $startIndex = 1;
        $finish = false;
        $resultHash = [];

        while (!$finish) {
            $optParams = [
                'dimensions' => 'ga:pagePath',
                'sort' => '-ga:pageviews',
                'max-results' => '10000',
                'start-index' => $startIndex,
            ];

            $result = $analytics->data_ga->get('ga:'.$profileId, '2014-01-01', 'today',
                'ga:pageviews,ga:uniquePageviews,ga:timeOnPage,ga:bounces,ga:entrances,ga:exits', $optParams);
            $rows = $result->rows;
            if (count($rows) < 10000) {
                $finish = true;
            }
            foreach ($rows as $row) {
                $resultHash[$row[0]] = [
                    'pv' => $row[1],
                    'uniquePv' => $row[2],
                    'timeOnPage' => $row[3],
                    'bounce' => $row[4],
                    'entrance' => $row[5],
                    'exit' => $row[6],
                ];
            }
            $startIndex += 10000;
            if ($startIndex > 100000) {
                $finish = true;
            }
        }

        return $resultHash;
    }
}

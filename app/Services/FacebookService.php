<?php

use \Facebook\Facebook;
use \Facebook\Exceptions;

class FacebookService
{

    public function getFacebookInstance()
    {
        $config = \Config::get('facebook');
        $fb = new Facebook([
            'app_id'                => $config['appId'],
            'app_secret'            => $config['secret'],
            'default_graph_version' => 'v2.5',
        ]);
        return $fb;
    }

    /**
     * @param  string $url
     * @return string
     */
    public function getAuthRedirectUrl($url)
    {
        $fb = $this->getFacebookInstance();
        $helper = $fb->getRedirectLoginHelper();
        $permissions = ['email', 'user_about_me'];
        $loginUrl = $helper->getLoginUrl($url, $permissions);

        return $loginUrl;
    }


    public function getFacebookAccessToken()
    {
        $fb = $this->getFacebookInstance();
        $helper = $fb->getRedirectLoginHelper();
        $accessToken = null;
        try {
            $accessToken = $helper->getAccessToken();
        } catch (Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
        } catch (Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
        }
        return (string)$accessToken;
    }
}
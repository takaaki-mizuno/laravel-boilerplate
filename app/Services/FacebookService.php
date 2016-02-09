<?php namespace App\Services;

use \Facebook\Facebook;
use \Facebook\Exceptions;
use \Facebook\PersistentData\FacebookSessionPersistentDataHandler;
use \Facebook\PersistentData\PersistentDataInterface;

class LaravelFacebookSessionPersistentDataHandler extends FacebookSessionPersistentDataHandler implements PersistentDataInterface
{

    protected $sessionPrefix = 'FBRLH_';

    public function __construct($enableSessionCheck = true)
    {
    }

    public function get($key)
    {
        if (!empty(\Session::get($this->sessionPrefix . $key))) {
            return \Session::get($this->sessionPrefix . $key);
        }

        return null;
    }

    public function set($key, $value)
    {
        \Session::put($this->sessionPrefix . $key, $value);
    }
}

class FacebookService
{

    public function getFacebookInstance()
    {
        $config = \Config::get('facebook');
        $fb = new Facebook([
            'app_id'                  => $config['appId'],
            'app_secret'              => $config['secret'],
            'default_graph_version'   => 'v2.5',
            'persistent_data_handler' => new LaravelFacebookSessionPersistentDataHandler(),
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

    /**
     * @return string
     */
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

    public function getMe($token)
    {
        $fb = $this->getFacebookInstance();
        $fb->setDefaultAccessToken($token);
        $userNode = null;
        try {
            $response = $fb->sendRequest('GET', '/me', ['fields' => 'name,email,id']);
            $userNode = $response->getGraphUser();
        } catch (\Facebook\Exceptions\FacebookResponseException $e) {
            // When Graph returns an error
            \Log::info('Graph returned an error: ' . $e->getMessage());
        } catch (\Facebook\Exceptions\FacebookSDKException $e) {
            // When validation fails or other local issues
            \Log::info('Facebook SDK returned an error: ' . $e->getMessage());
        }

        return $userNode;
    }
}
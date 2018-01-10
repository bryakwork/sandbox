<?php
/**
 * Created by PhpStorm.
 * User: USER_T
 * Date: 10.01.2018
 * Time: 18:33
 */

namespace rollun\ebayTools;

use DTS\eBaySDK\OAuth\Services\OAuthService;

class OAuthTransciever
{
    protected $oauth;

    public function __construct(OAuthService $oauth)
    {

        $this->oauth = $oauth;
    }

    public function __invoke()
    {
        $response = $this->oauth->getAppToken();
        $response = $response->toArray();
        $token = $response['access_token'];
        return $token;
    }
}
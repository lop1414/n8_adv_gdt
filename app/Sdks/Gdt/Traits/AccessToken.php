<?php

namespace App\Sdks\Gdt\Traits;

use App\Common\Enums\SystemAliasEnum;
use App\Common\Tools\CustomException;
use App\Services\Gdt\GdtAccountService;

trait AccessToken
{
    /**
     * @var
     * access token
     */
    protected $accessToken;

    /**
     * @param $accessToken
     * @return bool
     * 设置 access token
     */
    public function setAccessToken($accessToken){
        $this->accessToken = $accessToken;
        return true;
    }

    /**
     * @return mixed
     * @throws CustomException
     * 获取 access token
     */
    public function getAccessToken(){
        if(is_null($this->accessToken)){
            throw new CustomException([
                'code' => 'NOT_FOUND_ACCESS_TOKEN',
                'message' => '尚未设置access_token',
                'log' => true,
            ]);
        }
        return $this->accessToken;
    }


    public function getOauthAccessToken($appId,$secret,$authCode,$redirectUri){
        $url = $this->getUrl('/oauth/token');

        $param = [
            'client_id'     => $appId,
            'client_secret' => $secret,
            'grant_type'    => 'authorization_code',
            'authorization_code' => $authCode,
            'redirect_uri'  => $redirectUri
        ];

        return $this->authRequest($url, $param);
    }


    public function refreshAccessToken($appId,$secret,$refreshToken){
        $url = $this->getUrl('/oauth/token');

        $this->setAccessToken('');
        $param = [
            'client_id'     => $appId,
            'client_secret' => $secret,
            'grant_type'    => 'refresh_token',
            'refresh_token' => $refreshToken,
        ];

        return $this->authRequest($url, $param);
    }
}

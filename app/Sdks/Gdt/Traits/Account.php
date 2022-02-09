<?php

namespace App\Sdks\Gdt\Traits;

use App\Common\Tools\CustomException;

trait Account
{
    /**
     * @var
     * 账户id
     */
    protected $accountId;

    /**
     * @param $accountId
     * @return bool
     * 设置账户id
     */
    public function setAccountId($accountId){
        $this->accountId = $accountId;
        return true;
    }

    /**
     * @return mixed
     * @throws CustomException
     * 获取账户id
     */
    public function getAccountId(){
        if(is_null($this->accountId)){
            throw new CustomException([
                'code' => 'NOT_FOUND_ACCOUNT_ID',
                'message' => '尚未设置账户id',
            ]);
        }
        return $this->accountId;
    }


    public function getAccountList($token,$accountId,$page = 1){
        $url = $this->getUrl('/v1.3/business_manager_relations/get');

        $this->setAccessToken($token);
        $param = [
            'page'      => $page,
            'page_size' => 100
        ];
        return $this->authRequest($url, $param, 'GET');
    }
}

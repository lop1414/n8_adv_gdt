<?php

namespace App\Sdks\Gdt\Traits;

trait Multi
{
    /**
     * @param $url
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取分页列表
     */
    public function multiGetPageList($url, array $accounts, $page = 1, $pageSize = 10, $param = []){
        $curlOptions = [];
        foreach($accounts as $account){
            $p = array_merge([
                'account_id' => $account['account_id'],
                'access_token' => $account['access_token'],
                'timestamp' => time(),
                'nonce' => md5(uniqid()),
                'page' => $page,
                'page_size' => $pageSize,
            ], $param);

            $curlOptions[] = [
                'url' => $url,
                'param' => $p,
                'method' => 'GET',
                'header' => [
                    'Content-Type: application/json; charset=utf-8',
                ]
            ];
        }

        return $this->multiPublicRequest($curlOptions);
    }
}

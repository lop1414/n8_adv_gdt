<?php

namespace App\Sdks\Gdt\Traits;

trait Campaign
{


    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取广告计划列表
     */
    public function multiGetCampaignList(array $accounts, $page = 1, $pageSize = 10, $param = []){
        $url = $this->getUrl('/v1.3/campaigns/get');

        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }




}

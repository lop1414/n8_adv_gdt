<?php

namespace App\Services\Gdt\Report;

use App\Common\Tools\CustomException;
use App\Models\Gdt\Report\GdtAdReportModel;

class GdtAdReportService extends GdtReportService
{
    /**
     * OceanAccountReportService constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);

        $this->modelClass = GdtAdReportModel::class;
    }

    /**
     * @param $accounts
     * @param $page
     * @param $pageSize
     * @param array $param
     * @return mixed|void
     * sdk批量获取列表
     */
    public function sdkMultiGetList($accounts, $page, $pageSize, $param = []){
        return $this->sdk->multiGetAdReportList($accounts, $page, $pageSize, $param);
    }



    /**
     * @param $accountIds
     * @param string $date
     * @return array|mixed
     * @throws CustomException
     * 按账户消耗执行
     */
    protected function runByAccountCost($accountIds, $date){
        $gdtAccountReportService = new GdtAccountReportService();
        $accountReportMap = $gdtAccountReportService->getAccountReportByDate($date)->pluck('cost', 'account_id');

        $creativeReportMap = $this->getAccountReportByDate($date)->pluck('cost', 'account_id');

        $creativeAccountIds = [];
        foreach($accountReportMap as $accountId => $cost){
            if(isset($creativeReportMap[$accountId]) && bcsub($creativeReportMap[$accountId] * 100, $cost * 100) >= 0){
                continue;
            }
            $creativeAccountIds[] = $accountId;
        }

        return $creativeAccountIds;
    }
}

<?php

namespace App\Services\Gdt\Report;

use App\Models\Gdt\Report\GdtAccountDailyBalanceReportModel;


class GdtAccountDailyBalanceReportService extends GdtReportService
{

    public $pageSize = 100;

    public function __construct($appId = ''){
        parent::__construct($appId);

        $this->modelClass = GdtAccountDailyBalanceReportModel::class;
    }

    /**
     * @param array $accounts
     * @param $page
     * @param $pageSize
     * @param array $param
     * @return mixed|void
     * sdk批量获取列表
     */
    public function sdkMultiGetList($accounts, $page, $pageSize, $param = []){
        return $this->sdk->multiGetDailyBalanceReportList($accounts, $page, $pageSize, $param);
    }


    protected function itemFormat(&$item){
        $item['stat_datetime'] = date('Y-m-d H:i:s',$item['time']);
    }

    protected function itemValid($item){
        $valid = true;

        if(
            empty($item['deposit']) &&
            empty($item['paid']) &&
            empty($item['trans_in']) &&
            empty($item['trans_out']) &&
            empty($item['credit_modify']) &&
            empty($item['balance']) &&
            empty($item['preauth_balance'])
        ){
            $valid = false;
        }

        return $valid;
    }
}

<?php

namespace App\Services\Gdt\Report;

use App\Models\Gdt\Report\GdtAccountReportModel;

class GdtAccountReportService extends GdtReportService
{

    public function __construct($appId = ''){
        parent::__construct($appId);

        $this->modelClass = GdtAccountReportModel::class;
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
        return $this->sdk->multiGetAccountReportList($accounts, $page, $pageSize, $param);
    }
}

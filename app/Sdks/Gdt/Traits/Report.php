<?php

namespace App\Sdks\Gdt\Traits;

trait Report
{
    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取账户报表
     */
    public function multiGetAccountReportList(array $accounts, $page = 1, $pageSize = 100, $param = []){
        $url = $this->getUrl('v1.3/daily_reports/get');

        $param['level'] = 'REPORT_LEVEL_ADVERTISER';
        $param['group_by'] = ['date'];
        $param['time_line'] = 'REPORTING_TIME';
        if(!isset($param['fields'])){
            $param['fields'] =  [
                'account_id','date','view_count', 'valid_click_count','cost',
                'conversions_count','conversions_by_display_count',
                'from_follow_uv','biz_follow_uv','biz_consult_count'
            ];
        }

        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }

    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取广告报表
     */
    public function multiGetAdReportList(array $accounts, $page = 1, $pageSize = 100, $param = []){
        $url = $this->getUrl('v1.3/hourly_reports/get');
        $param['level'] = 'REPORT_LEVEL_AD';
        $param['group_by'] = ['hour','ad_id'];
        $param['time_line'] = 'REPORTING_TIME';

        if(!isset($param['fields'])){
            $param['fields'] =  [
                'account_id','hour','campaign_id','adgroup_id','ad_id',
                'view_count', 'valid_click_count','cost',
                'conversions_count','conversions_by_display_count',
                'from_follow_uv','biz_follow_uv','biz_consult_count'
            ];
        }
        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }


    /**
     * @param array $accounts
     * @param int $page
     * @param int $pageSize
     * @param array $param
     * @return mixed
     * 并发获取资金账户日结明细
     */
    public function multiGetDailyBalanceReportList(array $accounts, $page = 1, $pageSize = 100, $param = []){
        $url = $this->getUrl('v1.3/daily_balance_report/get');


        if(!isset($param['fields'])){
            $param['fields'] =  [
                'account_id','fund_type','time','deposit','paid',
                'trans_in', 'trans_out','credit_modify', 'balance','preauth_balance',
            ];
        }
        return $this->multiGetPageList($url, $accounts, $page, $pageSize, $param);
    }
}

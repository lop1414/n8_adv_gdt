<?php

namespace App\Services\Gdt;

use App\Common\Enums\StatusEnum;
use App\Common\Services\BaseService;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAccountModel;
use App\Sdks\Gdt\Gdt;

class GdtService extends BaseService
{
    /**
     * @var Gdt
     */
    public $sdk;

    /**
     * constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct();

        $this->sdk = new Gdt();

        if(!empty($appId)){
            $this->setAppId($appId);
        }
    }

    /**
     * @param $appId
     * @return bool
     * 设置应用id
     */
    public function setAppId($appId){
        return $this->sdk->setAppId($appId);
    }

    /**
     * @return mixed
     * 获取应用id
     */
    public function getAppId(){
        return $this->sdk->getAppId();
    }

    /**
     * @param $accountId
     * @return bool
     * 设置账户id
     */
    public function setAccountId($accountId){
        return $this->sdk->setAccountId($accountId);
    }

    /**
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 获取账户id
     */
    public function getAccountId(){
        return $this->sdk->getAccountId();
    }

    /**
     * @throws CustomException
     * 设置 access_token (请求前必须调用)
     */
    protected function setAccessToken(){
        $accountId = $this->getAccountId();

        // 获取账户信息
        $gdtAccountModel = new GdtAccountModel();
        $gdtAccount = $gdtAccountModel->where('app_id', $this->sdk->getAppId())
            ->where('account_id', $accountId)
            ->first();

        if(empty($gdtAccount)){
            throw new CustomException([
                'code' => 'NOT_FOUND_GDT_ACCOUNT',
                'message' => "找不到该广点通账户{{$accountId}}",
            ]);
        }


        // 设置token
        $this->sdk->setAccessToken($gdtAccount->access_token);
    }

    /**
     * @param $gdtAccount
     * @return bool
     * access_token是否已失效
     */
    public function isFailAccessToken($gdtAccount){
        $datetime = date('Y-m-d H:i:s', time());
        return $datetime > $gdtAccount->fail_at;
    }


    /**
     * @param array $accountIds
     * @return array
     * 获取账号组
     */
    public function getAccountGroup(array $accountIds = []){
        $gdtAccountModel = new GdtAccountModel();
        $builder = $gdtAccountModel->where('status', StatusEnum::ENABLE);

        if(!empty($accountIds)){
            $accountIdsStr = implode("','", $accountIds);
            $builder->whereRaw("account_id IN ('{$accountIdsStr}')");
        }

        $accounts = $builder->get()->toArray();

        $groupSize = 10;
        $group = array_chunk($accounts, $groupSize);

        return $group;
    }

    /**
     * @param $accounts
     * @param $page
     * @param $pageSize
     * @param array $param
     * @throws CustomException
     * sdk批量获取列表
     */
    public function sdkMultiGetList($accounts, $page, $pageSize, $param = []){
        throw new CustomException([
            'code' => 'PLEASE_WRITE_SDK_MULTI_GET_LIST_CODE',
            'message' => '请书写sdk批量获取列表代码',
        ]);
    }

    /**
     * @param $accounts
     * @param $pageSize
     * @param array $param
     * @return array
     * @throws CustomException
     * 并发获取分页列表
     */
    public function multiGetPageList($accounts, $pageSize, $param = []){
        // 账户映射
        $accountMap = array_column($accounts, null, 'account_id');

        // 账户第一页数据
        $res = $this->sdkMultiGetList($accounts, 1, $pageSize, $param);

        // 查询其他页数
        $more = [];
        foreach($res as $v){
            if(empty($v['req']['param'])){
                continue;
            }
            $reqParam = $v['req']['param'];

            $totalPage = 1;
            if(!empty($v['data']['page_info']['total_page'])){
                $totalPage = $v['data']['page_info']['total_page'];
            }
            $accountId = $reqParam['account_id'] ?? 0;

            if($accountId > 0 && $totalPage > 1){
                for($i = 2; $i <= $totalPage; $i++){
                    $more[$i][] = $accountMap[$accountId];
                }
            }
        }

        // 多页数据
        foreach($more as $page => $accounts){
            $tmp = $this->sdkMultiGetList($accounts, $page, $pageSize, $param);
            $res = array_merge($res, $tmp);
        }

        // 后置处理
        $res = $this->multiGetPageListAfter($res);

        // 数据过滤
        $list = [];
        foreach($res as $v){
            if(empty($v['data']['list']) || empty($v['req']['param'])){
                continue;
            }
            $reqParam = $v['req']['param'];

            foreach($v['data']['list'] as $item){
                $item['account_id'] = $reqParam['account_id'];
                $list[] = $item;
            }
        }
        return $list;
    }

    /**
     * @param $res
     * @return mixed
     * 并发获取分页列表后置处理
     */
    public function multiGetPageListAfter($res){
        return $res;
    }


    /**
     * @param $param
     * @return bool
     * 并发分片大小
     */
    public function setSdkMultiChunkSize($param){
        if(!empty($param['multi_chunk_size'])){
            $multiChunkSize = min(intval($param['multi_chunk_size']), 8);
            $this->sdk->setMultiChunkSize($multiChunkSize);
        }
        return true;
    }
}

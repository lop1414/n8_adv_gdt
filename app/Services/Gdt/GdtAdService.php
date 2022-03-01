<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAdModel;

class GdtAdService extends GdtService
{
    /**
     * constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);
        $this->model = GdtAdModel::class;
    }



    /**
     * @param $accounts
     * @param $page
     * @param $pageSize
     * @param array $param
     * @return mixed
     * sdk并发获取列表
     */
    public function sdkMultiGetList($accounts, $page, $pageSize, $param = []){
        return $this->sdk->multiGetAdList($accounts, $page, $pageSize, $param);
    }

    /**
     * @param array $param
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($param = []){
        // 并发分片大小
        $this->setSdkMultiChunkSize($param);

        $accountGroup = $this->getAccountGroup($param['account_ids']);

        $t = microtime(1);

        $pageSize = 100;
        foreach($accountGroup as $g){
            $ads = $this->multiGetPageList($g, $pageSize, $param);
            Functions::consoleDump('count:'. count($ads));
            // 保存
            $data = [];
            foreach($ads as $ad) {
                $ad['extends'] = json_encode($ad);
                $ad['audit_spec'] = json_encode($ad['audit_spec']);
                $ad['id'] = $ad['ad_id'];
                $ad['name'] = $ad['ad_name'];
                $ad['audit_spec'] = $ad['audit_spec'] ?? [];
                $ad['created_time'] = date('Y-m-d H:i:s',$ad['created_time']);
                $ad['last_modified_time'] = date('Y-m-d H:i:s',$ad['last_modified_time']);
                $ad['created_at'] = $ad['updated_at'] =  date('Y-m-d H:i:s');
                $data[] = $ad;
            }
            $this->chunkInsertOrUpdate($data,true);
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }
}

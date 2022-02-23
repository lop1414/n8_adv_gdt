<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAdgroupModel;

class GdtAdgroupService extends GdtService
{
    /**
     * constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);
        $this->model = GdtAdgroupModel::class;
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
        return $this->sdk->multiGetAdgroupList($accounts, $page, $pageSize, $param);
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
            $adgroups = $this->multiGetPageList($g, $pageSize, $param);

            Functions::consoleDump('count:'. count($adgroups));

            // 保存
            $data = [];
            foreach($adgroups as $adgroup) {
                $adgroup['extends'] = json_encode($adgroup);
                $adgroup['site_set'] = json_encode($adgroup['site_set']);
                $adgroup['id'] = $adgroup['adgroup_id'];
                $adgroup['name'] = $adgroup['adgroup_name'];
                $adgroup['bid_mode'] = $adgroup['bid_mode'] ?? '';
                $adgroup['created_time'] = date('Y-m-d H:i:s',$adgroup['created_time']);
                $adgroup['last_modified_time'] = date('Y-m-d H:i:s',$adgroup['last_modified_time']);
                $adgroup['created_at'] = $adgroup['updated_at'] =  date('Y-m-d H:i:s');
                $data[] = $adgroup;
            }
            $this->chunkInsertOrUpdate($data,true);
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }
}

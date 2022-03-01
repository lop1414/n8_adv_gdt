<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAdcreativeModel;

class GdtAdCreativeService extends GdtService
{
    /**
     * constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);
        $this->model = GdtAdcreativeModel::class;
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
        return $this->sdk->multiGetCreativeList($accounts, $page, $pageSize, $param);
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
            $creatives = $this->multiGetPageList($g, $pageSize, $param);
            Functions::consoleDump('count:'. count($creatives));

            // 保存
            $data = [];
            foreach($creatives as $creative){
                $creative['extends'] = json_encode($creative);
                $creative['site_set'] = json_encode($creative['site_set']);

                $creative['id'] = $creative['adcreative_id'];
                $creative['name'] = $creative['adcreative_name'];
                $creative['link_name_type'] = $creative['link_name_type'] ?? '';
                $creative['link_page_type'] = $creative['link_page_type'] ?? '';
                $creative['created_time'] = date('Y-m-d H:i:s',$creative['created_time']);
                $creative['last_modified_time'] = date('Y-m-d H:i:s',$creative['last_modified_time']);

                $creative['created_at'] = $creative['updated_at'] =  date('Y-m-d H:i:s');
                $data[] = $creative;
            }

            $this->chunkInsertOrUpdate($data,true);
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }
}

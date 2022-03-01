<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtConversionModel;

class GdtConversionService extends GdtService
{
    /**
     * constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);
        $this->model = GdtConversionModel::class;
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
        return $this->sdk->multiGetConversionList($accounts, $page, $pageSize, $param);
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
        unset($param['account_ids']);

        $t = microtime(1);

        $pageSize = 10;
        foreach($accountGroup as $g){
            $conversions = $this->multiGetPageList($g, $pageSize, $param);
            Functions::consoleDump('count:'. count($conversions));

            // 保存
            $data = [];
            foreach($conversions as $conversion) {

                $conversion['id'] = $conversion['conversion_id'];
                $conversion['name'] = $conversion['conversion_name'];
                $conversion['conversion_scene'] = $conversion['conversion_scene'] ?? '';
                $conversion['user_action_set_id'] = $conversion['user_action_set_id'] ?? '';
                $conversion['user_action_set_key'] = $conversion['user_action_set_key'] ?? '';
                $conversion['created_at'] = $conversion['updated_at'] =  date('Y-m-d H:i:s');
                $data[] = $conversion;
            }
            $this->chunkInsertOrUpdate($data,true);

        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }
}

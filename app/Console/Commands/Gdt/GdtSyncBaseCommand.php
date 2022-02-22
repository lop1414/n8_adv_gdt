<?php

namespace App\Console\Commands\Gdt;

use App\Common\Console\BaseCommand;
use App\Common\Helpers\Functions;

class GdtSyncBaseCommand extends BaseCommand
{

    /**
     * @param $param
     * @return array
     * 获取参数
     */
    public function filterParam($param){

        if(!empty($param['update_date'])){
            $date =  Functions::getDate($param['update_date']);
            $param['update_date'] = strtotime($date .' 00:00:00');
        }

        // 账户id过滤
        $param['account_ids'] = !empty($param['account_ids'])
            ? explode(",", $param['account_ids'])
            : [];

        //删除
        unset($param['key_suffix'],$param['help'],$param['quiet'],$param['verbose']);
        unset($param['version'],$param['ansi'],$param['no-ansi'],$param['no-interaction'],$param['env']);

        return $param;
    }


    /**
     * @param $key
     * @param $param
     * @return mixed|string
     * 获取锁下标
     */
    public function getLockKey($key,$param){
        if(!empty($param['key_suffix'])){
            $key .= '_'. trim($param['key_suffix']);
        }
        return $key;
    }

}

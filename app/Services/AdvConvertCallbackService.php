<?php

namespace App\Services;

use App\Common\Enums\ConvertCallbackTimeEnum;
use App\Common\Enums\ConvertTypeEnum;
use App\Common\Enums\CpTypeEnums;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Common\Services\ConvertCallbackService;
use App\Enums\Gdt\GdtCallbackObjectEnum;
use App\Sdks\Gdt\Gdt;

class AdvConvertCallbackService extends ConvertCallbackService
{

    protected $sdk;

    /**
     * OceanVideoService constructor.
     */
    public function __construct(){
        parent::__construct();
        $this->sdk = new Gdt();
    }


    /**
     * @param $item
     * @return bool
     * @throws CustomException
     * 回传
     */
    protected function callback($item){
        $eventTypeMap = $this->getEventTypeMap();

        if(!isset($eventTypeMap[$item->convert_type])){
            // 无映射
            throw new CustomException([
                'code' => 'UNDEFINED_EVENT_TYPE_MAP',
                'message' => '未定义的事件类型映射',
                'log' => true,
                'data' => [
                    'item' => $item,
                ],
            ]);
        }

        // 关联点击
        if(empty($item->click)){
            throw new CustomException([
                'code' => 'NOT_FOUND_CONVERT_CLICK',
                'message' => '找不到该转化对应点击',
                'log' => true,
                'data' => [
                    'item' => $item,
                ],
            ]);
        }

        $eventType = $eventTypeMap[$item->convert_type];

        $actionParam = [];
        //书籍信息
        if(!empty($item->extends->convert->n8_union_user->cp_book_id)){
            $actionParam['product_id'] =  $item->extends->convert->n8_union_user->cp_book_id;
            $bookName = $item->extends->convert->n8_union_user->book_name;
            $cpTypeName = $this->readCpTypeName($item->extends->convert->n8_union_user->cp_type);
            $actionParam['product_name'] =  $bookName.'-'.$cpTypeName;
        }

        //付费金额
        if(!empty($item->extends->convert->amount)){
            $actionParam['value'] =  $item->extends->convert->amount;
        }

        //上报方式
        $actionParam['object'] = GdtCallbackObjectEnum::FIRST_PAY_NO_LIMIT;
        if(!empty($item->extends->strategy->time_range)){
            $timeRange =  $item->extends->strategy->time_range;
            $actionParam['object'] = $this->getCallbackObjectParam($timeRange);
        }

        $eventTime = strtotime($item->convert_at);
        $this->runCallback($item->click,$eventType,$eventTime,$actionParam);

        return true;
    }



    public function runCallback($click,$actionType,$actionTime,$actionParam = []){
        $action = [
            'action_time' => strtotime($actionTime),
            'user_id' => [
                'hash_imei' => '',
                'hash_idfa' => '',
                'hash_android_id' => $click->android_id,
                'oaid' => '',
                'hash_oaid' => $click->oaid_md5
            ],
            'action_type' => $actionType,
            'click_id' => $click->adv_click_id,
            'action_param' => $actionParam
        ];
        $url = 'http://tracking.e.qq.com/conv';

        !empty($click->link) && $action['link'] = urlencode($click->link);
        !empty($click->callback) && $url = $click->callback;

        $param = ['actions' => [$action]];
        $this->sdk->callback($url,$param);
        return true;
    }



    /**
     * @return array
     * 获取事件映射
     */
    public function getEventTypeMap(){
        return [
            ConvertTypeEnum::ACTIVATION => 'VIEW_CONTENT',
            ConvertTypeEnum::REGISTER => 'ACTIVATE_APP',
            ConvertTypeEnum::FOLLOW => 'REGISTER',
            ConvertTypeEnum::ADD_DESKTOP => 'ADD_DESKTOP',
            ConvertTypeEnum::PAY => 'PURCHASE',
        ];
    }


    /**
     * @param $timeRangeEnum
     * @return mixed|string
     * 获取上报方式
     */
    public function getCallbackObjectParam($timeRangeEnum){
        $res = 'read_0';
        $map = [
            ConvertCallbackTimeEnum::TODAY => GdtCallbackObjectEnum::FIRST_PAY_TODAY,
            ConvertCallbackTimeEnum::HOUR_24 => GdtCallbackObjectEnum::FIRST_PAY_24,
            ConvertCallbackTimeEnum::HOUR_72 => GdtCallbackObjectEnum::FIRST_PAY_72,
        ];
        if(isset($map[$timeRangeEnum])){
            $res = $map[$timeRangeEnum];
        }

        return $res;
    }


    /**
     * @param $cpType
     * @return mixed
     * @throws CustomException
     * 获取产品平台名称
     */
    public function readCpTypeName($cpType){
        Functions::hasEnum(CpTypeEnums::class,$cpType);
        $map = array_column(CpTypeEnums::$list, null, 'id');
        return $map[$cpType]['name'];
    }



    /**
     * @param $click
     * @return array|void
     */
    public function filterClickData($click){
        return [
            'id' => $click['id'],
            'campaign_id' => $click['campaign_id'],
            'ad_id' => $click['adgroup_id'],
            'creative_id' => $click['creative_id'],
            'click_at' => $click['click_at'],
        ];
    }
}

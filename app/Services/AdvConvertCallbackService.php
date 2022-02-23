<?php

namespace App\Services;

use App\Common\Enums\ConvertTypeEnum;
use App\Common\Tools\CustomException;
use App\Common\Services\ConvertCallbackService;
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

        //付费金额
        $payAmount = 0;
        if(!empty($payAmount)){
            $payAmount =  $item->extends->amount;
        }

        $eventTime = strtotime($item->convert_at);
        $this->runCallback($item->click,$eventType,$eventTime,$payAmount);

        return true;
    }



    public function runCallback($click,$actionType,$actionTime,$payAmount = 0){
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
        ];
        $url = 'http://tracking.e.qq.com/conv';

        !empty($payAmount) && $action['action_param'] = ['value'=>$payAmount];
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
            ConvertTypeEnum::ACTIVATION => 'ACTIVATE_APP',
            ConvertTypeEnum::REGISTER => 'ACTIVATE_APP',
            ConvertTypeEnum::FOLLOW => 'REGISTER',
            ConvertTypeEnum::ADD_DESKTOP => 'REGISTER',
            ConvertTypeEnum::PAY => 'PURCHASE',
        ];
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

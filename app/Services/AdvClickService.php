<?php

namespace App\Services;

use App\Common\Enums\AdvClickSourceEnum;
use App\Common\Helpers\Functions;
use App\Common\Models\ClickModel;
use App\Common\Services\ClickService;
use App\Common\Tools\CustomException;
use App\Enums\QueueEnums;
use Jenssegers\Agent\Agent;

class AdvClickService extends ClickService
{
    /**
     * constructor.
     */
    public function __construct(){
        parent::__construct(QueueEnums::CLICK);
    }

    /**
     * @param $data
     * @return mixed
     * @throws CustomException
     * 数据过滤
     */
    public function dataFilter($data){
        // 验证
        $this->validRule($data, [
            'click_source' => 'required',
        ]);
        Functions::hasEnum(AdvClickSourceEnum::class, $data['click_source']);

        $clickAt = null;
        if(!empty($data['click_at'])){
            if(!is_numeric($data['click_at'])){
                throw new CustomException([
                    'code' => 'CLICK_AT_IS_ERROR',
                    'message' => '点击时间格式错误',
                    'log' => true,
                    'data' => $data,
                ]);
            }

            if($data['click_source'] == AdvClickSourceEnum::N8_AD_PAGE){
                $data['click_at'] = $data['click_at']/1000;
            }

            $clickAt = date('Y-m-d H:i:s', $data['click_at']);
            if(!Functions::timeCheck($clickAt)){
                throw new CustomException([
                    'code' => 'CLICK_AT_IS_ERROR',
                    'message' => '点击时间格式错误',
                    'log' => true,
                    'data' => $data,
                ]);
            }
        }

        if(empty($clickAt)){
            throw new CustomException([
                'code' => 'CLICK_AT_IS_NULL',
                'message' => '点击时间不能为空',
                'log' => true,
                'data' => $data
            ]);
        }

        if($data['click_source'] == AdvClickSourceEnum::ADV_CLICK_API){
            // 广告商api
            $data['os'] = $data['os'] ?? '';
            if($data['os'] == 'ios' && !empty($data['ios_channel_id'])){
                // IOS
                $data['channel_id'] = $data['ios_channel_id'];
            }elseif($data['os'] == 'android' && !empty($data['android_channel_id'])){
                // ANDROID
                $data['channel_id'] = $data['android_channel_id'];
            }else{
                $data['channel_id'] = 0;
            }
        }elseif($data['click_source'] == AdvClickSourceEnum::N8_AD_PAGE){
            // n8广告页
            $agent = new Agent();
            $agent->setUserAgent($data['ua']);

            if($agent->isIOS() && !empty($data['ios_channel_id'])){
                $data['channel_id'] = $data['ios_channel_id'];
            }elseif($agent->isAndroidOS() && !empty($data['android_channel_id'])){
                $data['channel_id'] = $data['android_channel_id'];
            }else{
                $data['channel_id'] = 0;
            }
        }
        if(!empty($data['link'])){
            if($data['link'] == base64_encode(base64_decode($data['link']))){
                $data['link'] = base64_decode($data['link']);
            }
        }

        if(!empty($data['callback'])){
                $data['callback'] = urldecode($data['callback']);
        }

        $data['click_at'] = $clickAt;
        return $data;
    }

    /**
     * @param $data
     * @return bool|void
     * 创建
     */
    protected function create($data){
        $clickModel = new ClickModel();
        $clickModel->click_source = $data['click_source'] ?? '';
        $clickModel->campaign_id = empty($data['campaign_id']) ? 0: $data['campaign_id'];
        $clickModel->adgroup_id = empty($data['adgroup_id']) ? 0: $data['adgroup_id'];
        $clickModel->ad_id = empty($data['ad_id']) ? 0: $data['ad_id'];
        $clickModel->click_id = $data['click_id'];
        $clickModel->request_id = $data['request_id'];
        $clickModel->channel_id = $data['channel_id'] ?? 0;
        $clickModel->muid = $data['muid'];
        $clickModel->android_id = $data['android_id'];
        $clickModel->oaid = $data['oaid'];
        $clickModel->oaid_md5 = $data['oaid_md5'];
        $clickModel->os = $data['os'];
        $clickModel->ip = $data['ip'];
        $clickModel->ua = $data['ua'];
        $clickModel->click_at = $data['click_at'];
        $clickModel->callback = $data['callback'];
        $clickModel->link = $data['link'] ?? '';
        $clickModel->extends = $data['extends'] ?? [];
        $ret = $clickModel->save();

        return $ret;
    }
}

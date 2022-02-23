<?php

namespace App\Services;

use App\Common\Enums\AdvClickSourceEnum;
use App\Common\Enums\ConvertCallbackTimeEnum;
use App\Common\Enums\ConvertTypeEnum;
use App\Common\Models\ClickModel;
use App\Common\Models\ConvertCallbackModel;
use App\Common\Services\ConvertMatchService;
use App\Models\Gdt\GdtAdgroupExtendModel;

class AdvConvertMatchService extends ConvertMatchService
{

    /**
     * 匹配规则
     */
    protected $matchBys = [
        self::MATCH_BY_REQUEST_ID,
        self::MATCH_BY_ADV_CLICK_ID,
        self::MATCH_BY_MUID,
        self::MATCH_BY_OAID_MD5,
        self::MATCH_BY_IP,
    ];

    /**
     * @param $click
     * @param $convert
     * @return array|mixed|void
     * 获取转化回传规则
     */
    protected function getConvertCallbackStrategy($click, $convert){
        // 转化类型
        $convertType = $convert['convert_type'];

        // 默认策略
        $strategy = [
            ConvertTypeEnum::PAY => [
                'time_range' => ConvertCallbackTimeEnum::TODAY,
                'convert_times' => 1,
                'callback_rate' => 100,
                'min_amount' => 20
            ],
        ];

        // 配置策略
        $adgroupId = $click->adgroup_id ?? 0;
        $adgroupExtend = GdtAdgroupExtendModel::find($adgroupId);
        if(!empty($adgroupExtend) && !empty($adgroupExtend->convert_callback_strategy()->enable()->first())){
            $strategy = $adgroupExtend->convert_callback_strategy['extends'];
        }

        $convertStrategy = $strategy[$convertType] ?? ['time_range' => ConvertCallbackTimeEnum::NEVER];

        return $convertStrategy;
    }

    /**
     * @param $click
     * @param $convert
     * @return mixed
     * 获取转化回传列表
     */
    protected function getConvertCallbacks($click, $convert){
        $clickDatetime = date('Y-m-d H:i:s', strtotime("-15 days", strtotime($convert['convert_at'])));
        $convertDate = date('Y-m-d', strtotime($convert['convert_at']));
        $convertRange = [
            $convertDate .' 00:00:00',
            $convertDate .' 23:59:59',
        ];

        $adgroupId = $click->adgroup_id ?? 0;

        $convertCallbackModel = new ConvertCallbackModel();
        $convertCallbacks = $convertCallbackModel->whereRaw("
            click_id IN (
                SELECT id FROM clicks
                    WHERE adgroup_id = '{$adgroupId}'
                        AND click_at BETWEEN '{$clickDatetime}' AND '{$convert['convert_at']}'
            ) AND convert_at BETWEEN '{$convertRange[0]}' AND '{$convertRange[1]}'
            AND convert_type IN ('{$convert['convert_type']}')
        ")->get();

        return $convertCallbacks;
    }

    /**
     * @param $data
     * @return ClickModel|void
     * 获取匹配查询构造器
     */
    protected function getMatchByBuilder($data){
        $builder = new ClickModel();

        $isTransfer = in_array( AdvClickSourceEnum::N8_TRANSFER, $this->clickSource) && count($this->clickSource) == 1;
        if(!$isTransfer){
            $channelId = $data['n8_union_user']['channel_id'] ?? 0;
            if(!empty($channelId)){
                $builder = $builder->whereRaw("
                    adgroup_id IN (
                        SELECT adgroup_id FROM channel_adgroups
                            WHERE channel_id = {$channelId}
                    )
                ");
            }
        }

        return $builder;
    }


}

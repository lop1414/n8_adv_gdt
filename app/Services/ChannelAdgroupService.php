<?php

namespace App\Services;

use App\Common\Enums\AdvAliasEnum;
use App\Common\Enums\PlatformEnum;
use App\Common\Helpers\Advs;
use App\Common\Helpers\Functions;
use App\Common\Models\ConvertCallbackStrategyGroupModel;
use App\Common\Models\ConvertCallbackStrategyModel;
use App\Common\Services\BaseService;
use App\Common\Services\SystemApi\NoticeApiService;
use App\Common\Services\SystemApi\UnionApiService;
use App\Common\Tools\CustomException;
use App\Models\Gdt\ChannelAdgroupLogModel;
use App\Models\Gdt\ChannelAdgroupModel;
use App\Models\Gdt\GdtAccountModel;
use App\Models\Gdt\GdtAdgroupModel;
use Illuminate\Support\Facades\DB;

class ChannelAdgroupService extends BaseService
{
    /**
     * @param $data
     * @return bool
     * @throws CustomException
     * 批量更新
     */
    public function batchUpdate($data){
        $this->validRule($data, [
            'channel_id' => 'required|integer',
            'adgroup_ids' => 'required|array',
            'channel' => 'required',
            'platform' => 'required'
        ]);

        Functions::hasEnum(PlatformEnum::class, $data['platform']);

        DB::beginTransaction();

        try{
            foreach($data['adgroup_ids'] as $adId){
                $this->update([
                    'adgroup_id' => $adId,
                    'channel_id' => $data['channel_id'],
                    'platform' => $data['platform'],
                    'extends' => [
                        'channel' => $data['channel'],
                    ],
                ]);
            }
        }catch(CustomException $e){
            DB::rollBack();
            throw $e;
        }catch(\Exception $e){
            DB::rollBack();
            throw $e;
        }

        DB::commit();

        return true;
    }

    /**
     * @param $data
     * @return bool
     * 更新
     */
    public function update($data){
        $channelAdgroupModel = new ChannelAdgroupModel();
        $channelAdgroup = $channelAdgroupModel->where('adgroup_id', $data['adgroup_id'])
            ->where('platform', $data['platform'])
            ->first();

        $flag = $this->buildFlag($channelAdgroup);
        if(empty($channelAdgroup)){
            $channelAdgroup = new ChannelAdgroupModel();
        }

        $channelAdgroup->adgroup_id = $data['adgroup_id'];
        $channelAdgroup->channel_id = $data['channel_id'];
        $channelAdgroup->platform = $data['platform'];
        $channelAdgroup->extends = $data['extends'];
        $ret = $channelAdgroup->save();

        if($ret && !empty($channelAdgroup->id) && $flag != $this->buildFlag($channelAdgroup)){
            $this->createChannelAdLog([
                'channel_adgroup_id' => $channelAdgroup->id,
                'adgroup_id' => $data['adgroup_id'],
                'channel_id' => $data['channel_id'],
                'platform' => $data['platform'],
                'extends' => $data['extends'],
            ]);
        }

        return $ret;
    }

    /**
     * @param $channelAdgroup
     * @return string
     * 构建标识
     */
    protected function buildFlag($channelAdgroup){
        $adminId = !empty($channelAdgroup->extends->channel->admin_id) ? $channelAdgroup->extends->channel->admin_id : 0;
        if(empty($channelAdgroup)){
            $flag = '';
        }else{
            $flag = implode("_", [
                $channelAdgroup->adgroup_id,
                $channelAdgroup->channel_id,
                $channelAdgroup->platform,
                $adminId
            ]);
        }
        return $flag;
    }

    /**
     * @param $data
     * @return bool
     * 创建渠道-计划日志
     */
    protected function createChannelAdLog($data){
        $channelAdgroupLogModel = new ChannelAdgroupLogModel();
        $channelAdgroupLogModel->channel_adgroup_id = $data['channel_adgroup_id'];
        $channelAdgroupLogModel->adgroup_id = $data['adgroup_id'];
        $channelAdgroupLogModel->channel_id = $data['channel_id'];
        $channelAdgroupLogModel->platform = $data['platform'];
        $channelAdgroupLogModel->extends = $data['extends'];
        return $channelAdgroupLogModel->save();
    }

    /**
     * @param $param
     * @return array
     * @throws CustomException
     * 列表
     */
    public function select($param){
        $this->validRule($param, [
            'start_datetime' => 'required',
            'end_datetime' => 'required',
        ]);

        $channelAdgroupModel = new ChannelAdgroupModel();
        $channelAdgroups = $channelAdgroupModel->whereBetween('updated_at', [$param['start_datetime'], $param['end_datetime']])->get();

        $distinct = $data = [];
        foreach($channelAdgroups as $channelAdgroup){
            if(empty($distinct[$channelAdgroup['channel_id']])){
                // 广告组
                $gdtAdgroup = GdtAdgroupModel::find($channelAdgroup['adgroup_id']);
                if(empty($gdtAdgroup)){
                    continue;
                }

                // 账户
                $gdtAccount = (new GdtAccountModel())->where('account_id', $gdtAdgroup['account_id'])->first();
                if(empty($gdtAccount)){
                    continue;
                }

                $data[] = [
                    'channel_id' => $gdtAdgroup['channel_id'],
                    'adgroup_id' => $gdtAdgroup['adgroup_id'],
                    'ad_name' => $gdtAdgroup['name'],
                    'account_id' => $gdtAdgroup['account_id'],
                    'account_name' => $gdtAccount['name'],
                    'admin_id' => $gdtAccount['admin_id'],
                ];
                $distinct[$gdtAdgroup['channel_id']] = 1;
            }
        }

        return $data;
    }

    /**
     * @param $data
     * @return array
     * @throws CustomException
     * 详情
     */
    public function read($data){
        $this->validRule($data, [
            'channel_id' => 'required|integer'
        ]);

        $channelAdgroupModel = new ChannelAdgroupModel();
        $adgroupIds = $channelAdgroupModel->where('channel_id', $data['channel_id'])->pluck('adgroup_id')->toArray();

        $builder = new GdtAdgroupModel();
        $builder = $builder->whereIn('id', $adgroupIds);

        // 过滤
        if(!empty($data['filtering'])){
            $builder = $builder->filtering($data['filtering']);
        }

        $adgroups = $builder->get();

        foreach($adgroups as $k => $v){
            unset($adgroups[$k]['extends']);
        }

        foreach($adgroups as $adgroup){
            if(!empty($adgroup->gdt_adgroup_extends)){
                $adgroup->convert_callback_strategy = ConvertCallbackStrategyModel::find($adgroup->gdt_adgroup_extends->convert_callback_strategy_id);
                $adgroup->convert_callback_strategy_group = ConvertCallbackStrategyGroupModel::find($adgroup->gdt_adgroup_extends->convert_callback_strategy_group_id);
            }else{
                $adgroup->convert_callback_strategy = null;
                $adgroup->convert_callback_strategy_group = null;
            }
        }

        return [
            'channel_id' => $data['channel_id'],
            'list' => $adgroups
        ];
    }

    /**
     * @param $param
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($param){
        $date = $param['date'];

        $startTime = date('Y-m-d H:i:s', strtotime('-2 hours', strtotime($date)));
        $endTime = "{$date} 23:59:59";

        $gdtAdgroupModel = new GdtAdgroupModel();
        $gdtAdgroups = $gdtAdgroupModel
            ->whereBetween('last_modified_time', [$startTime, $endTime])
            ->get();

        $keyword = 'sign='. Advs::getAdvClickSign(AdvAliasEnum::GDT);

        foreach($gdtAdgroups as $gdtAdgroup){
            $feedbackUrl = $gdtAdgroup->gdt_conversion->feedback_url ?? '';

            if(empty($feedbackUrl)){
                continue;
            }

            if(strpos($feedbackUrl, $keyword) === false){
                continue;
            }

            $ret = parse_url($feedbackUrl);
            parse_str($ret['query'], $param);
            $unionApiService = new UnionApiService();

            if(!empty($param['android_channel_id']) && !empty($param['ios_channel_id'])){
                // 安卓
                $channel = $unionApiService->apiReadChannel(['id' => $param['android_channel_id']]);
                $channelExtends = $channel['channel_extends'] ?? [];
                $channel['admin_id'] = $channelExtends['admin_id'] ?? 0;
                unset($channel['extends']);
                unset($channel['channel_extends']);

                $this->update([
                    'adgroup_id' => $gdtAdgroup->id,
                    'channel_id' => $param['android_channel_id'],
                    'platform' => PlatformEnum::ANDROID,
                    'extends' => [
                        'feedback_url' => $feedbackUrl,
                        'channel' => $channel,
                    ],
                ]);

                // iOS
                $channel = $unionApiService->apiReadChannel(['id' => $param['ios_channel_id']]);
                $channelExtends = $channel['channel_extends'] ?? [];
                $channel['admin_id'] = $channelExtends['admin_id'] ?? 0;
                unset($channel['extends']);
                unset($channel['channel_extends']);

                $this->update([
                    'adgroup_id' => $gdtAdgroup->id,
                    'channel_id' => $param['ios_channel_id'],
                    'platform' => PlatformEnum::IOS,
                    'extends' => [
                        'feedback_url' => $feedbackUrl,
                        'channel' => $channel,
                    ],
                ]);
            }else{

                if(!$gdtAdgroup->is_deleted){
                    $gdtAccountModel = new GdtAccountModel();
                    $gdtAccount = $gdtAccountModel->where('account_id', $gdtAdgroup->account_id)->first();

                    if(!empty($gdtAccount->admin_id)){
                        $title = "监测链错误";
                        $c = [
                            "账户id: {$gdtAccount->account_id}",
                            "账户名称: {$gdtAccount->name}",
                            "转化id: {$gdtAdgroup->gdt_conversion->id}",
                            "转化名称: {$gdtAdgroup->gdt_conversion->name}",
                            "当前计划监测链:{$feedbackUrl}",
                            "请在 联运系统 > 渠道 中复制正确监测链！！",
                        ];

                        $content = implode("<br>", $c);

                        $adminId = $gdtAccount->admin_id;

                        $noticeApiService = new NoticeApiService();
                        $noticeApiService->apiSendFeishuMessage($title, $content, $adminId, 1800);
                    }
                }
            }
        }

        return true;
    }
}

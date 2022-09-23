<?php

namespace App\Http\Controllers\Admin;

use App\Common\Controllers\Front\FrontController;
use App\Common\Enums\PlatformEnum;
use App\Common\Services\SystemApi\UnionApiService;
use App\Services\ChannelAdgroupService;
use Illuminate\Http\Request;

class ChannelAdgroupController extends FrontController
{
    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 批量更新
     */
    public function batchUpdate(Request $request){
        $data = $request->post();
        if(isset($data['channel_id'])){
            $data['channel'] = (new UnionApiService())->apiReadChannel(['id' => $data['channel_id']]);
        }

        $channelAdgroupService = new ChannelAdgroupService();
        $data['platform'] = PlatformEnum::ANDROID;
        $ret = $channelAdgroupService->batchUpdate($data);

        $data['platform'] =  PlatformEnum::IOS;
        $ret = $channelAdgroupService->batchUpdate($data);
        return $this->ret($ret);
    }

}

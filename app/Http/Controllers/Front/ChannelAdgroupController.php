<?php

namespace App\Http\Controllers\Front;

use App\Common\Controllers\Front\FrontController;
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

        $channelAdgroupService = new ChannelAdgroupService();
        $ret = $channelAdgroupService->batchUpdate($data);

        return $this->ret($ret);
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 列表
     */
    public function select(Request $request){
        $param = $request->post();

        $channelAdgroupService = new ChannelAdgroupService();
        $data = $channelAdgroupService->select($param);

        return $this->success($data);
    }
}

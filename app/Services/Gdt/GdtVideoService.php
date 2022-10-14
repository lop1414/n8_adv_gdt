<?php

namespace App\Services\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Enums\Gdt\GdtSyncTypeEnum;
use App\Models\Gdt\GdtAccountVideoModel;
use App\Models\Gdt\GdtVideoModel;
use App\Services\Task\TaskGdtSyncService;

class GdtVideoService extends GdtService
{
    /**
     * constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);
    }


    /**
     * @param $accountId
     * @param $signature
     * @param $file
     * @param string $filename
     * @return mixed
     * @throws CustomException
     * 上传
     */
    public function uploadVideo($accountId, $signature, $file, $filename = ''){
        $this->setAccessToken();

        $ret = $this->sdk->addVideo($accountId, $signature, $file, $filename);
        Functions::consoleDump($ret);

        // 同步
        if(!empty($ret['video_id'])){
            $taskOceanSyncService = new TaskGdtSyncService(GdtSyncTypeEnum::VIDEO);
            $task = [
                'name' => '同步广点通视频',
                'admin_id' => 0,
            ];
            $subs = [];
            $subs[] = [
                'app_id' => $this->sdk->getAppId(),
                'account_id' => $accountId,
                'admin_id' => 0,
                'extends' => [
                    'video_id' => $ret['video_id']
                ],
            ];
            $taskOceanSyncService->create($task, $subs);
        }

        return $ret;
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
        return $this->sdk->multiGetVideoList($accounts, $page, $pageSize, $param);
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

        foreach($accountGroup as $g){
            $videos = $this->multiGetPageList($g, 100, $param);
            Functions::consoleDump('count:'. count($videos));

            // 保存
            foreach($videos as $video) {
                $this->save($video);
            }
        }

        $t = microtime(1) - $t;
        var_dump($t);

        return true;
    }



    /**
     * @param $video
     * @return bool
     * 保存
     */
    public function save($video){
        $gdtVideoModel = new GdtVideoModel();
        $gdtVideo = $gdtVideoModel->where('id', $video['video_id'])->first();

        if(empty($gdtVideo)){
            $gdtVideo = new GdtVideoModel();
        }

        $gdtVideo->id = $video['video_id'];
        $gdtVideo->file_size = $video['file_size'];
        $gdtVideo->width = $video['width'];
        $gdtVideo->height = $video['height'];
        $gdtVideo->type = $video['type'];
        $gdtVideo->signature = $video['signature'];
        $gdtVideo->key_frame_image_url = $video['key_frame_image_url'];
        $gdtVideo->video_bit_rate = $video['video_bit_rate'];
        $gdtVideo->image_duration_millisecond = $video['image_duration_millisecond'];
        $gdtVideo->source_type = $video['source_type'];
        $gdtVideo->description = $video['description'];
        $gdtVideo->created_time = date('Y-m-d H:i:s',$video['created_time']);
        $ret = $gdtVideo->save();

        if($ret){
            // 添加关联关系
            $this->relationAccount($video['account_id'],$video['video_id'],$video['status']);
        }

        return $ret;
    }



    /**
     * @param $accountId
     * @param $videoId
     * @param $status
     * @return GdtAccountVideoModel
     * 关联账户
     */
    public function relationAccount($accountId,$videoId,$status){
        $gdtAccountVideoModel = new GdtAccountVideoModel();
        $gdtAccountVideo = $gdtAccountVideoModel
            ->where('account_id', $accountId)
            ->where('video_id', $videoId)
            ->first();

        if(empty($gdtAccountVideo)){
            $gdtAccountVideo = new GdtAccountVideoModel();
            $gdtAccountVideo->account_id = $accountId;
            $gdtAccountVideo->video_id = $videoId;
            $gdtAccountVideo->status = $status;
            $gdtAccountVideo->save();
        }elseif($gdtAccountVideo->status != $status){

            $gdtAccountVideo->status = $status;
            $gdtAccountVideo->save();
        }


        return $gdtAccountVideo;
    }
}

<?php

namespace App\Services\Task;

use App\Common\Enums\ExecStatusEnum;
use App\Enums\TaskTypeEnum;
use App\Common\Tools\CustomException;
use App\Services\Gdt\GdtVideoService;

class TaskGdtVideoUploadService extends TaskGdtService
{
    /**
     * OceanVideoUploadTaskService constructor.
     */
    public function __construct()
    {
        parent::__construct(TaskTypeEnum::GDT_VIDEO_UPLOAD);
    }

    /**
     * @param $taskId
     * @param $data
     * @return bool|void
     * @throws CustomException
     * 创建
     */
    public function createSub($taskId, $data){
        // 验证
        $this->validRule($data, [
            'app_id' => 'required',
            'account_id' => 'required',
            'n8_material_video_id' => 'required',
            'n8_material_video_path' => 'required',
            'n8_material_video_name' => 'required',
            'n8_material_video_signature' => 'required',
        ]);

        $subModel = new $this->subModelClass();
        $subModel->task_id = $taskId;
        $subModel->app_id = $data['app_id'];
        $subModel->account_id = $data['account_id'];
        $subModel->n8_material_video_id = $data['n8_material_video_id'];
        $subModel->n8_material_video_path = $data['n8_material_video_path'];
        $subModel->n8_material_video_name = $data['n8_material_video_name'];
        $subModel->n8_material_video_signature = $data['n8_material_video_signature'];
        $subModel->exec_status = ExecStatusEnum::WAITING;
        $subModel->admin_id = $data['admin_id'] ?? 0;
        $subModel->extends = $data['extends'] ?? [];

        return $subModel->save();
    }

    /**
     * @param $subTask
     * @return bool|void
     * @throws CustomException
     * 执行单个子任务
     */
    public function runSub($subTask){
        // 上传
        $uploadType = 'upload';

        // 下载
        $file = $this->download($subTask->n8_material_video_path);

        // 上传
        $gdtVideoService = new GdtVideoService($subTask->app_id);
        $gdtVideoService->setAccountId($subTask->account_id);
        $gdtVideoService->uploadVideo($subTask->account_id, $file['signature'], $file['curl_file'], $subTask->n8_material_video_name);

        // 删除临时文件
        unlink($file['path']);


        // 上传类型
        $subTask->extends = array_merge($subTask->extends, ['upload_type' => $uploadType]);

        return true;
    }



    /**
     * @param $fileUrl
     * @param $storageDir
     * @return array
     * 下载
     */
    public function download($fileUrl){
        $content = file_get_contents($fileUrl);

        $fileName = basename($fileUrl);
        $tmp = explode(".", $fileName);
        $suffix = end($tmp);

        // 临时文件保存目录
        $storageDir = storage_path('app/temp');
        if(!is_dir($storageDir)){
            mkdir($storageDir, 0755, true);
        }

        // 文件存放地址
        $path = $storageDir .'/'. md5(uniqid()) .'.'. $suffix;

        // 保存
        file_put_contents($path, $content);

        // 获取 mime_type
        $finfo = finfo_open(FILEINFO_MIME);
        $mimeType = finfo_file($finfo, $path);

        // 设置 mime_type
        $curlFile = new \CURLFile($path);
        $curlFile->setMimeType($mimeType);

        return [
            'path' => $path,
            'signature' => md5($content),
            'curl_file' => $curlFile,
        ];
    }
}

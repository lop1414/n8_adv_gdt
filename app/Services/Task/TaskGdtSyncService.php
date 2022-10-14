<?php

namespace App\Services\Task;

use App\Common\Enums\ExecStatusEnum;
use App\Enums\Gdt\GdtSyncTypeEnum;
use App\Enums\TaskTypeEnum;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Services\Gdt\GdtAccountService;
use App\Services\Gdt\GdtVideoService;

class TaskGdtSyncService extends TaskGdtService
{
    /**
     * @var
     * 同步类型
     */
    public $syncType;

    /**
     * constructor.
     * @param $syncType
     * @throws CustomException
     */
    public function __construct($syncType)
    {
        parent::__construct(TaskTypeEnum::GDT_SYNC);

        // 同步类型
        Functions::hasEnum(GdtSyncTypeEnum::class, $syncType);
        $this->syncType = $syncType;
    }

    /**
     * @param $taskId
     * @param $data
     * @return bool
     * @throws CustomException
     * 创建
     */
    public function createSub($taskId, $data){
        // 验证
        $this->validRule($data, [
            'app_id' => 'required',
            'account_id' => 'required',
        ]);

        // 校验
        Functions::hasEnum(GdtSyncTypeEnum::class, $this->syncType);

        $subModel = new $this->subModelClass();
        $subModel->task_id = $taskId;
        $subModel->app_id = $data['app_id'];
        $subModel->account_id = $data['account_id'];
        $subModel->sync_type = $this->syncType;
        $subModel->exec_status = ExecStatusEnum::WAITING;
        $subModel->admin_id = $data['admin_id'] ?? 0;
        $subModel->extends = $data['extends'] ?? [];

        return $subModel->save();
    }

    /**
     * @param $taskId
     * @return mixed
     * 获取待执行子任务
     */
    public function getWaitingSubTasks($taskId){
        $subModel = new $this->subModelClass();

        $builder = $subModel->where('task_id', $taskId)
            ->where('sync_type', $this->syncType);
//            ->where('exec_status', ExecStatusEnum::WAITING);

        if($this->syncType == GdtSyncTypeEnum::VIDEO){
            // 获取3分钟前创建的任务
            $time = time() - 3 * 60;
            $datetime = date('Y-m-d H:i:s', $time);
            $builder->where('created_at', '<', $datetime);
        }

        $subTasks = $builder->orderBy('id', 'asc')->get();

        return $subTasks;
    }

    /**
     * @param $subTask
     * @return bool|void
     * @throws CustomException
     * 执行单个子任务
     */
    public function runSub($subTask){
        if($this->syncType == GdtSyncTypeEnum::ACCOUNT){
            $this->syncAccount($subTask);
        }elseif($this->syncType == GdtSyncTypeEnum::VIDEO){
            $this->syncVideo($subTask);
        }else{
            throw new CustomException([
                'code' => 'NOT_HANDLE_FOR_SYNC_TYPE',
                'message' => '该同步类型无对应处理',
            ]);
        }

        return true;
    }


    /**
     * @param $subTask
     * @return bool
     * @throws CustomException
     * 同步广告账户
     */
    private function syncAccount($subTask){
        $gdtAccountService = new GdtAccountService($subTask->app_id);
        $option = [
            'app_id' => $subTask->app_id,
            'account_id' => $subTask->account_id,
        ];
        $gdtAccountService->sync($option);
        return true;
    }


    /**
     * @param $subTask
     * @return bool
     * @throws CustomException
     * 同步视频
     */
    private function syncVideo($subTask){
        $gdtVideoService = new GdtVideoService($subTask->app_id);

        $option = [
            'account_ids' => [$subTask->account_id],
        ];

        // 筛选视频id
        if(!empty($subTask->extends->video_id)){
            $option['ids'] = [$subTask->extends->video_id];
        }

        $gdtVideoService->sync($option);
        return true;
    }


}

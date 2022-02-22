<?php

namespace App\Services\Task;

use App\Common\Enums\ExecStatusEnum;
use App\Enums\Gdt\GdtSyncTypeEnum;
use App\Enums\TaskTypeEnum;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;

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
            ->where('sync_type', $this->syncType)
            ->where('exec_status', ExecStatusEnum::WAITING);

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
        throw new CustomException([
            'code' => 'NOT_HANDLE_FOR_SYNC_TYPE',
            'message' => '该同步类型无对应处理',
        ]);
    }


}

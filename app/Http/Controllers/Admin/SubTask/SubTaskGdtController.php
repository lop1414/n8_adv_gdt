<?php

namespace App\Http\Controllers\Admin\SubTask;

use App\Common\Controllers\Admin\SubTaskController;


class SubTaskGdtController extends SubTaskController
{
    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * 列表预处理
     */
    public function selectPrepare(){
        parent::selectPrepare();

        $this->curdService->selectQueryAfter(function(){
            foreach($this->curdService->responseData['list'] as $item){
                // 关联巨量账户
                $item->gdt_account;
            }
        });
    }

    /**
     * 详情预处理
     */
    public function readPrepare(){
        parent::readPrepare();
        $this->curdService->findAfter(function(){
            // 关联巨量账户
            $this->curdService->findData->gdt_account;
        });
    }
}

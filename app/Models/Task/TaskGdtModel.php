<?php

namespace App\Models\Task;

use App\Common\Models\SubTaskModel;

class TaskGdtModel extends SubTaskModel
{
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     * 关联巨量账户模型 一对一
     */
    public function gdt_account(){
        return $this->belongsTo('App\Models\Gdt\GdtAccountModel', 'account_id', 'account_id');
    }
}

<?php

namespace App\Models\Gdt;

use App\Common\Helpers\Functions;
use App\Common\Models\BaseModel;

class GdtModel extends BaseModel
{

    /**
     * @var array
     * 批量更新忽略字段
     */
    protected $updateIgnoreFields = ['created_at'];

    /**
     * @param $query
     * @return mixed
     * 数据授权
     */
    public function scopeWithPermission($query){
        $adminUserInfo = Functions::getGlobalData('admin_user_info');
        $table = $this->getTable();
        if(!$adminUserInfo['is_admin']){
            $query->whereRaw("
                {$table}.account_id IN (
                    SELECT account_id FROM ocean_accounts
                        WHERE admin_id = {$adminUserInfo['admin_user']['id']}
                )
            ");
        }
        return $query;
    }

    /**
     * @param $query
     * @return mixed
     * 管理员数据授权
     */
    public function scopeWithAdminPermission($query){
        $adminUserInfo = Functions::getGlobalData('admin_user_info');
        $table = $this->getTable();
        $query->whereRaw("
            {$table}.account_id IN (
                SELECT account_id FROM ocean_accounts
                    WHERE admin_id = {$adminUserInfo['admin_user']['id']}
            )
        ");
        return $query;
    }
}

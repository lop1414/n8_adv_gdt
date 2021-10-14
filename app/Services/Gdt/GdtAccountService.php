<?php

namespace App\Services\Gdt;

use App\Common\Enums\StatusEnum;
use App\Common\Enums\SystemAliasEnum;
use App\Common\Helpers\Functions;
use App\Common\Tools\CustomException;
use App\Enums\Gdt\GdtSyncTypeEnum;
use App\Models\AppModel;
use App\Models\Gdt\GdtAccountModel;
use App\Services\Task\TaskGdtSyncService;

class GdtAccountService extends GdtService
{
    /**
     * OceanVideoService constructor.
     * @param string $appId
     */
    public function __construct($appId = ''){
        parent::__construct($appId);
    }


    public function getRedirectUri(){
        return "http://ny.7788zongni.com/admin/adv-callback/gdt";
//        return config('common.system_api.'.SystemAliasEnum::ADV_GDT.'.url').'/front/gdt/grant';
    }



    /**
     * @param $authCode
     * @return bool
     * @throws CustomException
     * 授权
     */
    public function grant($authCode){
        $appId = $this->sdk->getAppId();

        $appModel = new AppModel();
        $app = $appModel->where('app_id', $appId)->first();
        if(empty($app)){
            throw new CustomException([
                'code' => 'NOT_FOUND_APP',
                'message' => '找不到对应app',
                'data' => [
                    'app_id' => $appId,
                ],
            ]);
        }


        if(!Functions::isLocal()){
            $this->sdk->setAccessToken('');
            $info = $this->sdk->getOauthAccessToken($appId,$app->secret, $authCode);
            var_dump($info);
        }else{
            $info = [
                'authorizer_info' => [
                    'account_id' => 'xxx',
                    'account_name' => 'xxx',
                ],
                'access_token'  => '111',
                'refresh_token' => '222',
                'access_token_expires_in' => 86399,
            ];
        }

        $gdtAccount = (new GdtAccountModel())
            ->where('app_id', $appId)
            ->where('account_id', $info['authorizer_info']['account_id'])
            ->first();

        if(empty($gdtAccount)){
            $gdtAccount = (new GdtAccountModel());
            $gdtAccount->parent_id = 0;
            $gdtAccount->status = StatusEnum::ENABLE;
            $gdtAccount->app_id = $appId;
            $gdtAccount->account_id = $info['authorizer_info']['account_id'];
            $gdtAccount->name = $info['authorizer_info']['account_name'];
        }


        $gdtAccount->access_token = $info['access_token'];
        $gdtAccount->refresh_token = $info['refresh_token'];
        $gdtAccount->fail_at = $info['access_token_expires_in'] == 999999999 ? '2038-01-01 00:00:00': date('Y-m-d H:i:s', time() + $info['access_token_expires_in'] - 2000);
        $gdtAccount->extend = [];
        $gdtAccount->save();

        // 创建任务
        $taskSyncService = new TaskGdtSyncService(GdtSyncTypeEnum::ACCOUNT);
        $task = [
            'name' => "广点通账户同步",
            'admin_id' => 0,
        ];

        $subs = [];
        $subs[] = [
            'app_id' => $appId,
            'account_id' => $info['authorizer_info']['account_id'],
            'admin_id' => $task['admin_id']
        ];
        $taskSyncService->create($task, $subs);

        $this->sync(['account_id' => $info['authorizer_info']['account_id']]);
        return true;
    }


    /**
     * @return bool
     * @throws CustomException
     * 批量同步
     */
    public function batchSync(){
        $gdtParentAccounts = (new GdtAccountModel())->where('parent_id',0)->get();


        // 创建任务
        $taskKsSyncService = new TaskGdtSyncService(GdtSyncTypeEnum::ACCOUNT);
        $task = [
            'name' => "广点通账户同步",
            'admin_id' => 0,
        ];
        $subs = [];
        foreach($gdtParentAccounts as $parentAccount){
            $subs[] = [
                'app_id'    => $parentAccount->app_id,
                'account_id'=> $parentAccount->account_id,
                'admin_id' => $task['admin_id']
            ];
        }

        $taskKsSyncService->create($task, $subs);

        return true;
    }


    /**
     * @param $option
     * @return bool
     * @throws CustomException
     * 同步
     */
    public function sync($option){
        $gdtParentAccount = (new GdtAccountModel())
            ->where('account_id',$option['account_id'])
            ->first();
        $accounts = $this->sdk->getAccountList($gdtParentAccount->access_token,$gdtParentAccount->account_id);

        foreach($accounts['list'] as $account){

            $gdtAccount = (new GdtAccountModel())
                ->where('app_id',$gdtParentAccount['app_id'])
                ->where('account_id',$account['account_id'])
                ->first();

            if(empty($gdtAccount)){
                $gdtAccount = new GdtAccountModel();
                $gdtAccount->app_id = $gdtParentAccount['app_id'];
                $gdtAccount->account_id = $account['account_id'];
                $gdtAccount->parent_id = $gdtParentAccount['account_id'];
                $gdtAccount->admin_id = 0;
                $gdtAccount->status = StatusEnum::ENABLE;
            }

            $gdtAccount->name = $account['account_id'];
            $gdtAccount->company = $account['corporation_name'];
            $gdtAccount->extend = [];
            $gdtAccount->access_token = $gdtParentAccount['access_token'];
            $gdtAccount->refresh_token = '';
            $gdtAccount->fail_at = $gdtParentAccount['fail_at'];
            $gdtAccount->save();
        }
        return true;
    }



    /**
     * @return bool
     * 刷新 access token
     */
    public function refreshAccessToken(){
        $gdtParentAccounts = (new GdtAccountModel())->where('fail_at','<=',date('Y-m-d H:i:s'))->get();
        foreach($gdtParentAccounts as $gdtParentAccount){
            $app = (new AppModel())->where('app_id',$gdtParentAccount->app_id)->first();
            if(!Functions::isLocal()){
                $info = $this->sdk->refreshAccessToken($gdtParentAccount->app_id, $app->secret, $gdtParentAccount->refresh_token);
                var_dump($info);
            }else{
                $info = [
                    'authorizer_info' => [
                        'account_id' => 'xxx',
                        'account_name' => 'xxx',
                    ],
                    'access_token'  => '111',
                    'refresh_token' => '222',
                    'access_token_expires_in' => 86399,
                ];
            }

            $gdtParentAccount->access_token = $info['access_token'];
            $gdtParentAccount->refresh_token = $info['refresh_token'];
            $gdtParentAccount->fail_at = $info['access_token_expires_in'] == 999999999 ? '2038-01-01 00:00:00': date('Y-m-d H:i:s', time() + $info['access_token_expires_in'] - 2000);
            $gdtParentAccount->save();

            $gdtAccounts = (new GdtAccountModel())->where('parent_id',$gdtParentAccount->account_id)->get();
            foreach ($gdtAccounts as $gdtAccount){
                $gdtAccount->access_token = $gdtParentAccount['access_token'];
                $gdtAccount->refresh_token = '';
                $gdtAccount->fail_at = $gdtParentAccount->fail_at;
                $gdtAccount->save();
            }
        }

        return true;
    }
}

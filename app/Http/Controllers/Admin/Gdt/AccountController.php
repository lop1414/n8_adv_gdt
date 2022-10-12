<?php

namespace App\Http\Controllers\Admin\Gdt;

use App\Common\Enums\StatusEnum;
use App\Common\Helpers\Functions;
use App\Common\Services\SystemApi\CenterApiService;
use App\Common\Tools\CustomException;
use App\Models\Gdt\GdtAccountModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class AccountController extends GdtController
{
    /**
     * @var string
     * 默认排序字段
     */
    protected $defaultOrderBy = 'account_id';

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new GdtAccountModel();

        parent::__construct();
    }

    /**
     * 分页列表预处理
     */
    public function selectPrepare(){
        parent::selectPrepare();

        $this->curdService->selectQueryBefore(function(){
            $this->curdService->customBuilder(function($builder){
                $this->filter();
            });
        });

        $this->curdService->selectQueryAfter(function(){
            // 获取管理员列表
            $centerApiService = new CenterApiService();
            $adminUsers = $centerApiService->apiGetAdminUsers();

            // 映射
            $adminUserMap = array_column($adminUsers, null, 'id');

            foreach($this->curdService->responseData['list'] as $k => $v){
                $this->curdService->responseData['list'][$k]['admin_name'] = isset($adminUserMap[$v->admin_id]) ? $adminUserMap[$v->admin_id]['name'] : '';
            }
        });
    }

    /**
     * 列表预处理
     */
    public function getPrepare(){
        parent::getPrepare();

        $this->curdService->getQueryBefore(function(){
            $this->filter();
        });
    }

    /**
     * 过滤
     */
    private function filter(){
        $this->curdService->customBuilder(function($builder){
            // 关键词
            $keyword = $this->curdService->requestData['keyword'] ?? '';
            if(!empty($keyword)){
                $builder->whereRaw("(account_id LIKE '%{$keyword}%' OR name LIKE '%{$keyword}%')");
            }

            $builder->where('parent_id', '<>', 0);
        });
    }

    /**
     * 更新预处理
     */
    public function updatePrepare(){
        $this->curdService->addField('name')->addValidRule('required|max:16|min:2');
        $this->curdService->addField('admin_id')->addValidRule('required');

        $this->curdService->saveBefore(function (){
            $this->model->existWithoutSelf('name',$this->curdService->handleData['name'],$this->curdService->handleData['id']);

            // 验证admin id
            $adminInfo = (new CenterApiService())->apiReadAdminUser($this->curdService->handleData['admin_id']);
            if($adminInfo['status'] != StatusEnum::ENABLE){
                throw new CustomException([
                    'code' => 'ADMIN_DISABLE',
                    'message' => '该后台用户已被禁用'
                ]);
            }
        });

        // 限制修改的字段
        $this->curdService->handleAfter(function (){
            foreach($this->curdService->handleData as $field => $val){
                if(!in_array($field,['name','admin_id','id'])){
                    unset($this->curdService->handleData[$field]);
                }
            }
        });
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CustomException
     * 批量更新管理员
     */
    public function batchUpdateAdmin(Request $request){
        $requestData = $request->post();
        $this->validRule($requestData, [
            'ids' => 'required|array',
            'admin_id' => 'required',
        ]);

        $gdtAccountModel = new GdtAccountModel();
        $builder = $gdtAccountModel->whereIn('id', $requestData['ids']);

        if($builder->count() == 0){
            throw new CustomException([
                'code' => 'NOT_FOUND_ACCOUNT',
                'message' => '找不到对应账户',
            ]);
        }

        $builder->update(['admin_id' => $requestData['admin_id']]);

        return $this->success();
    }
}

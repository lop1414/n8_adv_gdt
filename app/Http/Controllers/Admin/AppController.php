<?php

namespace App\Http\Controllers\Admin;

use App\Common\Controllers\Admin\AdminController;
use App\Common\Enums\StatusEnum;
use App\Common\Tools\CustomException;
use App\Models\AppModel;
use App\Services\Gdt\GdtAccountService;


class AppController extends AdminController
{
    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new AppModel();

        parent::__construct();
    }

    public function getAuthUrl($appId){
        $redirectUri = (new GdtAccountService())->getRedirectUri();

        $url = 'https://developers.e.qq.com/oauth/authorize?';
        $url .= http_build_query([
            'client_id' => $appId,
            'state'     => $appId,
            'redirect_uri' => $redirectUri
        ]);
        return $url;
    }


    /**
     * 分页列表预处理
     */
    public function selectPrepare(){
        parent::selectPrepare();

        $this->curdService->selectQueryAfter(function(){
            foreach ($this->curdService->responseData['list'] as $item){
                $item->auth_url = $this->getAuthUrl($item['app_id']);
            }
        });
    }


    /**
     * 创建预处理
     */
    public function createPrepare(){
        $this->curdService->addField('app_id')->addValidRule('required');
        $this->curdService->addField('secret')->addValidRule('required');
        $this->curdService->saveBefore(function(){
            if($this->curdService->getModel()->uniqueExist([
                'app_id' => $this->curdService->handleData['app_id']
            ])){
                throw new CustomException([
                    'code' => 'DATA_EXIST',
                    'message' => 'app id 已存在'
                ]);
            }

            if(empty($this->curdService->handleData['status'])){
                $this->curdService->handleData['status'] = StatusEnum::ENABLE;
            }
        });
    }


    /**
     * 更新预处理
     */
    public function updatePrepare(){
        $this->curdService->saveBefore(function (){
            $this->model->existWithoutSelf('name',$this->curdService->handleData['name'],$this->curdService->handleData['id']);
            $this->model->existWithoutSelf('app_id',$this->curdService->handleData['app_id'],$this->curdService->handleData['id']);
        });
    }
}

<?php

namespace App\Http\Controllers\Front\Gdt;

use App\Common\Controllers\Front\FrontController;
use App\Common\Enums\ExceptionTypeEnum;
use App\Common\Services\ErrorLogService;
use App\Services\Gdt\GdtAccountService;
use Illuminate\Http\Request;

class IndexController extends FrontController
{
    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }



    /**
     * @param Request $request
     * @return mixed
     * @throws \App\Common\Tools\CustomException
     * 授权
     */
    public function grant(Request $request){
        $data = $request->all();
        $this->validRule($data, [
            'authorization_code' => 'required'
        ]);

        $errorLogService = new ErrorLogService();
        $errorLogService->create('GDT_OAUTH_GRANT_LOG', '广点通Oauth授权日志', $data, ExceptionTypeEnum::CUSTOM);

        $appId = $data['state'];
        $authCode = $data['authorization_code'];

        $ret = (new GdtAccountService($appId))->grant($authCode);

        return $this->ret($ret);
    }

}

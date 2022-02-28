<?php

namespace App\Http\Controllers\Admin;

use App\Common\Controllers\Admin\AdminController;
use App\Common\Enums\ConvertTypeEnum;
use App\Common\Models\ClickModel;
use App\Common\Tools\CustomException;
use App\Enums\Gdt\GdtCallbackObjectEnum;
use App\Services\AdvConvertCallbackService;
use Illuminate\Http\Request;

class ClickController extends AdminController
{
    /**
     * @var string
     * 默认排序字段
     */
    protected $defaultOrderBy = 'click_at';

    /**
     * constructor.
     */
    public function __construct()
    {
        $this->model = new ClickModel();

        parent::__construct();
    }

    /**
     * 列表预处理
     */
    public function selectPrepare(){
        $this->curdService->selectQueryBefore(function(){
            $this->curdService->customBuilder(function($builder){
                // 2小时内
                $datetime = date('Y-m-d H:i:s', strtotime("-2 hours"));
                $builder->where('click_at', '>', $datetime);
            });
        });
    }

    /**
     * @param Request $request
     * @return mixed
     * @throws CustomException
     * 回传
     */
    public function callback(Request $request){
        $this->validRule($request->post(), [
            'event_type' => 'required',
            'channel_id' => 'required',
        ]);

        $eventType = $request->post('event_type');
        $channelId = $request->post('channel_id');

        $advConvertCallbackService = new AdvConvertCallbackService();
        $eventTypeMap = $advConvertCallbackService->getEventTypeMap();
        $eventTypes = array_values($eventTypeMap);
        if(!in_array($eventType, $eventTypes)){
            throw new CustomException([
                'code' => 'UNKNOWN_EVENT_TYPE',
                'message' => '非合法回传类型',
            ]);
        }

        $datetime = date('Y-m-d H:i:s', strtotime("-2 hours"));

        $click = (new ClickModel())->where('click_at', '>', $datetime)->where('channel_id', $channelId)->first();
        if(empty($click)){
            throw new CustomException([
                'code' => 'NOT_FOUND_CLICK',
                'message' => '找不到对应点击',
            ]);
        }

        $actionParam = [
            'product_id' =>  666666,
            'product_name' =>  '测试数据-测试平台',
            'object' =>  GdtCallbackObjectEnum::FIRST_PAY_NO_LIMIT,
        ];

        //付费金额
        if($eventType == $eventTypeMap[ConvertTypeEnum::PAY]){
            $actionParam['value'] =  0.1;
        }

        $ret = (new AdvConvertCallbackService())->runCallback($click, $eventType,date('Y-m-d H:i:s'),$actionParam);

        return $this->ret($ret);
    }

}

<?php

namespace App\Http\Controllers;

use App\Common\Controllers\Front\FrontController;


use App\Common\Helpers\Advs;
use App\Common\Models\ClickModel;
use App\Services\AdvConvertCallbackService;
use App\Services\Gdt\GdtAccountService;
use App\Services\Gdt\GdtAdCreativeService;
use App\Services\Gdt\GdtAdService;
use App\Services\Gdt\GdtConversionService;
use Illuminate\Http\Request;

class TestController extends FrontController
{
    /**
     * constructor.
     */
    public function __construct()
    {
        parent::__construct();
    }



    public function test(Request $request){
        $key = $request->input('key');
        if($key != 'aut'){
            return $this->forbidden();
        }

        $click = (new ClickModel())->first();
        $click->callback = 'http://tracking.e.qq.com/conv?cb=cYfkL18KKPrRqXdqSsh6TbBCY2dU57p5KbvN-3R0mps%3D&conv_id=3199063';
        $click->android_id = '';
        $click->oaid_md5 = '';
        $click->adv_click_id = 'd37bkyqbaaal7ovuj2hq';
        (new AdvConvertCallbackService())->runCallback($click,'PURCHASE','2022-02-23 17:30:02',0.8);
    }








}

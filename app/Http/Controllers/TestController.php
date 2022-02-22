<?php

namespace App\Http\Controllers;

use App\Common\Controllers\Front\FrontController;


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

        $ret = (new GdtConversionService('1110428552'))->sync(['account_ids' => [17814411]]);
        dd($ret);
    }








}

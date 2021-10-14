<?php

namespace App\Http\Controllers;

use App\Common\Controllers\Front\FrontController;



use App\Services\Gdt\GdtAccountService;
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
        $ret = (new GdtAccountService('1110428552'))->grant('de987ac5f94cbf5fca1c85f25d1bc1a9');
//        $ret = (new GdtAccountService('1110428552'))->sync(['account_id'=> 17978113]);
//        $ret = (new GdtAccountService('1110428552'))->refreshAccessToken();
        dd($ret);
    }








}

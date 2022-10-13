<?php

namespace App\Sdks\Gdt;


use App\Sdks\Gdt\Traits\AccessToken;
use App\Sdks\Gdt\Traits\Account;
use App\Sdks\Gdt\Traits\Ad;
use App\Sdks\Gdt\Traits\Adgroup;
use App\Sdks\Gdt\Traits\App;
use App\Sdks\Gdt\Traits\Campaign;
use App\Sdks\Gdt\Traits\Conversion;
use App\Sdks\Gdt\Traits\Creative;
use App\Sdks\Gdt\Traits\Image;
use App\Sdks\Gdt\Traits\Multi;
use App\Sdks\Gdt\Traits\Report;
use App\Sdks\Gdt\Traits\Request;
use App\Sdks\Gdt\Traits\Video;

class Gdt
{

    use App;
    use AccessToken;
    use Request;
    use Account;
    use Multi;
    use Campaign;
    use Adgroup;
    use Creative;
    use Image;
    use Video;
    use Ad;
    use Conversion;
    use Report;


    /**
     * 公共接口地址
     */
    const BASE_URL = 'https://api.e.qq.com/';

    /**
     * @param $uri
     * @return string
     * 获取请求地址
     */
    public function getUrl($uri){
        return self::BASE_URL .'/'. ltrim($uri, '/');
    }


    /**
     * @param $appId
     * @param $redirectUri
     * @return string
     * 获取授权回调地址
     */
    public function getAuthUrl($appId,$redirectUri){
        $url = 'https://developers.e.qq.com/oauth/authorize?';
        $url .= http_build_query([
            'client_id' => $appId,
            'state'     => $appId,
            'redirect_uri' => $redirectUri
        ]);
        return $url;
    }


    /**
     * @param $url
     * @param $param
     * @return bool
     * @throws \App\Common\Tools\CustomException
     * 转化回传
     */
    public function callback($url,$param){
        $header = [
            'Content-Type: application/json',
            'cache-control: no-cache',
        ];

        $this->publicRequest($url, $param, 'POST', $header);
        return true;
    }


    /**
     * @param $param
     * @return mixed
     * 过滤请求参数
     */
    public function filterParam($param){

        // 更新时间
        if(!empty($param['update_date'])){
            $date = strtotime($param['update_date'] .' 00:00:00');
            $param['filtering'] = [
                [
                    'field' => 'last_modified_time',
                    'operator' => 'GREATER_EQUALS',
                    'values'   => [$date]
                ]
            ];
            unset($param['update_date']);
        }

        // 状态
        if(!empty($param['status'])){
            $param['filtering'] = [
                [
                    'field' => 'status',
                    'operator' => 'EQUALS',
                    'values'   => [$param['status']]
                ]
            ];
            unset($param['status']);
        }

        //是否已删除
        if(!empty($param['is_deleted'])){
            $param['is_deleted'] = 'true';
        }

        return $param;
    }
}

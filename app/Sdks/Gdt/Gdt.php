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
use App\Sdks\Gdt\Traits\Request;

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
    use Ad;
    use Conversion;


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
}

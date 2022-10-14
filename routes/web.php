<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});

// 后台需授权接口
$router->group([
    'prefix' => 'admin',
    'middleware' => ['center_menu_auth', 'admin_request_log', 'access_control_allow_origin']
], function () use ($router) {

    $router->group(['prefix' => 'app'], function () use ($router) {
        $router->post('select', 'Admin\AppController@select');
        $router->post('create', 'Admin\AppController@create');
        $router->post('update', 'Admin\AppController@update');
        $router->post('enable', 'Admin\AppController@enable');
        $router->post('disable', 'Admin\AppController@disable');
    });

    // 广点通
    $router->group(['prefix' => 'gdt'], function () use ($router) {
        // 账户
        $router->group(['prefix' => 'account'], function () use ($router) {
            $router->post('select', 'Admin\Gdt\AccountController@select');
            $router->post('get', 'Admin\Gdt\AccountController@get');
            $router->post('read', 'Admin\Gdt\AccountController@read');
            $router->post('update', 'Admin\Gdt\AccountController@update');
            $router->post('enable', 'Admin\Gdt\AccountController@enable');
            $router->post('disable', 'Admin\Gdt\AccountController@disable');
            $router->post('delete', 'Admin\Gdt\AccountController@delete');
            $router->post('batch_enable', 'Admin\Gdt\AccountController@batchEnable');
            $router->post('batch_disable', 'Admin\Gdt\AccountController@batchDisable');
            $router->post('batch_update_admin', 'Admin\Gdt\AccountController@batchUpdateAdmin');
        });

        // 广告
        $router->group(['prefix' => 'adgroup'], function () use ($router) {
            $router->post('select', 'Admin\Gdt\AdgroupController@select');
            $router->post('read', 'Admin\Gdt\AdgroupController@read');
        });

        // 广告扩展
        $router->group(['prefix' => 'adgroup_extend'], function () use ($router) {
            $router->post('create', 'Admin\Gdt\AdgroupExtendController@create');
            $router->post('update', 'Admin\Gdt\AdgroupExtendController@update');
            $router->post('select', 'Admin\Gdt\AdgroupExtendController@select');
            $router->post('read', 'Admin\Gdt\AdgroupExtendController@read');
            $router->post('batch_update', 'Admin\Gdt\AdgroupExtendController@batchUpdate');
        });


        // 视频
        $router->group(['prefix' => 'video'], function () use ($router) {
            $router->post('upload', 'Admin\Gdt\VideoController@upload');
            $router->post('batch_upload', 'Admin\Gdt\VideoController@batchUpload');
        });
    });


    // 渠道-计划
    $router->group(['prefix' => 'channel_adgroup'], function () use ($router) {
        $router->post('batch_update', 'Admin\ChannelAdgroupController@batchUpdate');
    });

    // 回传策略
    $router->group(['prefix' => 'convert_callback_strategy'], function () use ($router) {
        $router->post('create', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@create');
        $router->post('update', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@update');
        $router->post('select', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@select');
        $router->post('get', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@get');
        $router->post('read', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@read');
    });


    // 回传策略组
    $router->group(['prefix' => 'convert_callback_strategy_group'], function () use ($router) {
        $router->post('create', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyGroupController@create');
        $router->post('update', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyGroupController@update');
        $router->post('select', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyGroupController@select');
        $router->post('get', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyGroupController@get');
        $router->post('read', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyGroupController@read');
    });
    // 点击
    $router->group(['prefix' => 'click'], function () use ($router) {
        $router->post('select', 'Admin\ClickController@select');
        $router->post('callback', 'Admin\ClickController@callback');
    });

    // 转化回传
    $router->group(['prefix' => 'convert_callback'], function () use ($router) {
        $router->post('callback', '\\App\Common\Controllers\Admin\ConvertCallbackController@callback');
    });
});


// 前台接口
$router->group([
    'prefix' => 'front',
    'middleware' => ['api_sign_valid', 'access_control_allow_origin']
], function () use ($router) {
    // 转化
    $router->group(['prefix' => 'convert'], function () use ($router) {
        $router->post('match', '\\App\Common\Controllers\Front\ConvertController@match');
    });

    // 转化回传
    $router->group(['prefix' => 'convert_callback'], function () use ($router) {
        $router->post('get', '\\App\Common\Controllers\Front\ConvertCallbackController@get');
    });

    // 渠道-计划
    $router->group(['prefix' => 'channel_adgroup'], function () use ($router) {
        $router->post('select', 'Front\ChannelAdgroupController@select');
        $router->post('batch_update', 'Front\ChannelAdgroupController@batchUpdate');
    });
});


$router->group(['middleware' => ['access_control_allow_origin']], function () use ($router) {
    // 点击
    $router->get('front/click', 'Front\AdvClickController@index');
});

//广点通授权回调
$router->get('front/gdt/grant', 'Front\Gdt\IndexController@grant');

// 测试
$router->post('test', 'TestController@test');

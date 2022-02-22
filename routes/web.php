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


    // 回传策略
    $router->group(['prefix' => 'convert_callback_strategy'], function () use ($router) {
        $router->post('create', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@create');
        $router->post('update', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@update');
        $router->post('select', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@select');
        $router->post('get', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@get');
        $router->post('read', '\\App\Common\Controllers\Admin\ConvertCallbackStrategyController@read');
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

$router->group(['middleware' => ['access_control_allow_origin']], function () use ($router) {
    // 点击
    $router->get('front/click', 'Front\AdvClickController@index');
});

//广点通授权回调
$router->get('front/gdt/grant', 'Front\Gdt\IndexController@grant');

// 测试
$router->post('test', 'TestController@test');

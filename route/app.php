<?php
// +----------------------------------------------------------------------
// | ThinkPHP [ WE CAN DO IT JUST THINK ]
// +----------------------------------------------------------------------
// | Copyright (c) 2006~2018 http://thinkphp.cn All rights reserved.
// +----------------------------------------------------------------------
// | Licensed ( http://www.apache.org/licenses/LICENSE-2.0 )
// +----------------------------------------------------------------------
// | Author: liu21st <liu21st@gmail.com>
// +----------------------------------------------------------------------
use think\facade\Route;

use app\middleware\DbMiddleware;
use yunwuxin\auth\middleware\Authentication;
use app\middleware\AuthMiddleware;


Route::group('api',function () {

    Route::group('auth', function () {
        //登陆
        Route::post('login', 'auth/login');
        //退出
        Route::get('loginOut', 'auth/loginOut');
        //退出站点
        Route::get('websiteOut', 'auth/websiteOut');
    });


    Route::group('admin',function () {
        /* 用户 */
        Route::group('user', function () {
            //列表
            Route::get('/', 'user/index');
            //添加用户
            Route::post('createUser','user/createUser');
            //编辑
            Route::post('updateUser','user/updateUser');
            //更改状态
            Route::get('statusOperation','user/statusOperation');
            //删除
            Route::get('delUser','user/delUser');
            //获取用户信息
            Route::get('getUserInfo','user/getUserInfo');
        });

        /* 角色 */
        Route::group('role', function () {
            //列表
            Route::get('/','role/index');
            //获取所有角色
            Route::get('getRole', 'role/getRole');
            //编辑
            Route::post('updateRole','role/updateRole');
            //创建
            Route::post('createRole','role/createRole');
            //删除
            Route::get('delRole','role/delRole');
            //分配权限
            Route::post('giveAccess','role/giveAccess');
        });

        /* 节点 */
        Route::group('node', function () {
            //列表
            Route::get('/','node/index');
            //编辑
            Route::post('updateNode','node/updateNode');
            //创建
            Route::post('createNode','node/createNode');
            //删除
            Route::get('delNode','node/delNode');
            //节点列表
            Route::get('getNodeTree','node/getNodeTree');
        });

  

        /* api */
        Route::group('index', function () {
            //栏目
            Route::get('getMenu','index/getMenu');
        });


    })->prefix('admin.');



    });//->middleware(Authentication::class);

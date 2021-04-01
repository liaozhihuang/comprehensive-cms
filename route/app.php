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

        //菜单
        Route::group('menu', function () {
            //列表
            Route::get('/','menu/index');
            //添加
            Route::post('createMenu','menu/createMenu');
            //更新
            Route::post('updateMenu','menu/updateMenu');
            //删除
            Route::get('delMenu','menu/delMenu');
            //操作
            Route::get('statusOperation','menu/statusOperation');
            //推荐
            Route::get('recommendOperation','menu/recommendOperation');
            //获取栏目
            Route::get('getMenu','menu/getMenu');
        });

        //菜单组
        Route::group('menuGroup', function () {
            //列表
            Route::get('/','menuGroup/index');
            //添加
            Route::post('createMenuGroup','menuGroup/createMenuGroup');
            //更新
            Route::post('updateMenuGroup','menuGroup/updateMenuGroup');
            //删除
            Route::get('delMenuGroup','menuGroup/delMenuGroup');
            //获取数据
            Route::get('getMenuGroup','menuGroup/getMenuGroup');
        });

        //广告列表
        Route::group('ad', function () {
            //列表
            Route::get('/','ad/index');
            //添加
            Route::post('createAd','ad/createAd');
            //更新
            Route::post('updateAd','ad/updateAd');
            //删除
            Route::get('delAd','ad/delAd');
            //更改状态
            Route::get('statusOperation','ad/statusOperation');
        });


        /* api */
        Route::group('index', function () {
            //栏目
            Route::get('getMenu','index/getMenu');
        });

        /*  上传  */
        Route::group('upload', function () {
            //上传图片 file
            Route::post('uploadImageSingle','upload/uploadImageSingle');
        });


    })->prefix('admin.');



    });//->middleware(Authentication::class);

<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class MenuValidate extends Validate
{
    protected $rule = [
        'id'    =>  'require|integer',
        'menu_name' =>  'require|max:30', //节点名称
        'menu_sign'    =>  'require|max:15|alphaDash', //标识
        'img_url' =>  'require',  //图片 
        'group_sign'  =>  'require', //菜单组🆔id
        'status'    =>  'require|integer', //状态
        'sort'  =>  'require|integer', //排序
        "recommend" =>  'require|integer',
        'pid'   =>  'require|integer',  //上级id
    ];

    protected $message = [
        'id.require'    =>  '主键id必须',
        'id.integer'    =>  '主键id参数类型错误',

        'menu_name.require' =>  '菜单名称必须',
        'menu_name.max' =>  '菜单名称长度不符合',

        'menu_sign.require' =>  '菜单标识必须',
        'menu_sign.alphaDash' =>  '菜单标识格式错误',
        'menu_sign.max' =>  '菜单标识长度不符合',

        'img_url.require' =>  '菜单图片必须',

        'group_sign.require'  =>  '所属组必须',

        'status.require'  =>  '请选择状态',
        'status.integer'   =>  '状态必须填写',

        'recommend.require'  =>  '是否推荐',
        'recommend.integer'   =>  '推荐格式错误',

        'sort.require'  =>  '排序必须填写',
        'sort.integer'   =>  '排序必须是正整数',

        'pid.require'    =>  '上级必须',
        'pid.integer'    =>  '上级参数类型错误',

    ];


    protected $scene = [
        //添加顶级
        'create'    =>  ['menu_name','menu_sign','img_url','group_sign','recommend','sort','status','pid'], 
        //修改
        'update'  =>  ['id','menu_name','img_url','group_sign','recommend','sort','status','pid'],
    ];
}

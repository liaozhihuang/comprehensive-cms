<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class MenuGroupValidate extends Validate
{

    protected $rule = [
        'id'    =>'require|integer',
        'group_name'    =>  'require|max:10',
        'group_sign'    =>  'require|max:15|alphaDash',

    ];
    protected $message = [
        'id.require'    =>  '主键id必须',
        'id.integer'    =>  '主键id格式错误',

        'group_name.require'    =>  '名称必须',
        'group_name.max'    =>  '名称长度不正确',
        
        'group_sign.require'    =>  '菜单组标识必须',
        'group_sign.alphaDash'    =>  '菜单组标识格式错误',
        'group_sign.max'    =>  '菜单组标识长度不正确'
    ];

    protected $scene = [
        'create'    =>  ['group_name','group_sign'],
        'update'  =>  ['id','group_name'],
    ];



}

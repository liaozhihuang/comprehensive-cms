<?php
declare(strict_types = 1);

namespace app\validate;

use think\Validate;

class  RoleValidate extends Validate
{

    protected $rule = [
        'id'    =>'require|integer',
        'role_name'    =>  'require|max:10',

    ];
    protected $message = [
        'id.require'    =>  '主键id必须',
        'id.integer'    =>  '主键id格式错误',

        'role_name.require'    =>  '角色名必须',
        'role_name.max'    =>  '角色名长度不正确',
    ];

    protected $scene = [
        'insert'    =>  ['role_name'],
        'update'  =>  ['id','role_name'],
    ];




}
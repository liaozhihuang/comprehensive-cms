<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class AdGroupValidate extends Validate
{
    protected $rule = [
        'id'    =>'require|integer',
        'group_name'    =>  'require|max:10',
    ];
    protected $message = [
        'id.require'    =>  '主键id必须',
        'id.integer'    =>  '主键id格式错误',

        'group_name.require'    =>  '名称必须',
        'group_name.max'    =>  '名称长度不正确',
    ];

    protected $scene = [
        'create'    =>  ['group_name'],
        'update'  =>  ['id','group_name'],
    ];
}

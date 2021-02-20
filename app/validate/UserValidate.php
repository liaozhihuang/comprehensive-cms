<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class UserValidate extends Validate
{
    protected $rule = [
        'id'    =>'require|integer',
        'user_name'    =>'require|max:10',
        'role'    =>  'require|integer',
        'password'    =>  'require|max:20|pass',
        'password2'    =>  'require|max:20',
        'real_name'    =>  'require|max:10',
        'status'    =>  'require|integer',
    ];
    protected $message = [
        'id.require'    =>  '主键id必须',
        'id.integer'    =>  '主键id格式错误',
        'user_name.require'    =>  '管理员账号必须',
        'user_name.max'    =>  '管理员账号长度不正确',
        'role.require'    =>  '角色必须',
        'role.integer'    =>  '角色格式错误',
        'password.require'    =>  '密码必须填写',
        'password.max'    =>  '密码长度不正确',
        'password.pass'    =>  '两次密码不一致',
        'password2.require'    =>  '确认密码必须填写',
        'password2.max'    =>  '确认密码长度不正确',
        'real_name.require'    =>  '真实姓名不正确',
        'real_name.max'    =>  '真实姓名长度不正确',
        'status.require'    =>  '管理员状态必须选择',
        'status.integer'    =>  '管理员状态格式错误',
    ];

    protected $scene = [
        'insert'    =>  ['user_name','role','password','password2','real_name','status'],
        'update'    =>  ['id','user_name','role','real_name','status'],
        'pass' =>   ['password','passowrd2'],
        'switch_status'  =>  ['id','status'],
    ];

    protected function pass($password,$value,$data)
    {   
        if($password != $data['password2']){
            return false;
        }
        return true;
    }


}

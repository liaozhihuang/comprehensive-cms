<?php
declare (strict_types = 1);

namespace app\model;

use library\basic\BaseModel;

/**
 * @mixin \think\Model
 */
class RoleModel extends BaseModel
{
    protected $name = "role";

    /**
     * 根据id 查询是否存在
     */
    public static function checkRoleId(int $role_id)
    {
        $data =  self::where('id',$role_id)->find();
        if(!$data){
            return self::fail('角色错误');
        }
        return $data;
    }

}

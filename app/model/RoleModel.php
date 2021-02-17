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

    /**
     * 获取全部角色
     */
    public function getRole():array
    {
        return self::order('id')->select()->toArray();
    }


     /**
     * 创建时间
     */
    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s',(int)$value);
    }


    /**
     * 列表
     */
    public function getRoleAll($where,$limit,$offset):array
    {
        return self::where($where)->order('add_time','desc')->limit($offset,$limit)->order('add_time','desc')->select()->toArray();
    }

    /**
     * 数量
     */
    public function getRoleTotal($where):int
    {
        return self::where($where)->count();
    }

    /**
     * 根据条件 查询角色是否存在
     */
    public function checkRoleName(array $where)
    {
        $data = self::where($where)->find();
        if($data){
            return self::fail('角色已经存在');
        }
    }

}

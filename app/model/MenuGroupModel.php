<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class MenuGroupModel extends Model
{
    protected $name = "menu_group";

    use SoftDelete;

    protected $deleteTime = 'delete_time';


    /**
     * 验证标识
     */
    public function checkMenuGroupSign(array $param)
    {
        $data = self::where('group_name',$param['group_name'])->whereOr('group_sign',$param['group_sign'])->find();
        if($data){
            return self::fail('名称或者标识已存在');
        }
    }

    /**
     * 创建
     */
    public function createMenuGroup(array $param)
    {
        return self::create($param);
    }

    /**
     * 验证🆔id 数据是否存在
     */
    public function checkMenuGroupId($id)
    {
        $data = self::where('id',$id)->find();
        if(empty($data)){
            return self::fail('未找到分组');
        }
        return $data;
    }

    /**
     * 验证
     */
    public function checkSign(string $sign)
    {
        $data = self::where('group_sign',$sign)->find();
        if(empty($data)){
            return self::fail('未找到分组');
        }
        return $data;
    }

    /**
     * 验证名称是否存在
     */
    public function checkMenuGroupName(array $param)
    {
        $data = self::where('id','<>',$param['id'])->where('group_name',$param['group_name'])->find();
        if($data){
            return self::fail('分组名称已存在');
        }
    }

    /**
     * 列表
     */
    public function getMenuGroupAll($where,$offset,$limit):array
    {
        return self::where($where)->limit($offset, $limit)->order('id desc')->select()->toArray();
    }

    /**
     * 数量
     */
    public function getMenuGroupTotal($where):int
    {
        return self::where($where)->count();
    }

    /**
     * 时间
     */
    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s',(int)$value);
    }

    /**
     * 获取列表
     */
    public function getMenuGroup():array
    {
        return self::order('id desc')->hidden(['add_time','update_time','delete_time'])->select()->toArray();
    }


}

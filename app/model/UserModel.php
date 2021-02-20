<?php
declare (strict_types = 1);

namespace app\model;

use library\basic\BaseModel;
use think\model\concern\SoftDelete;
use tauthz\facade\Enforcer;
use think\helper\Str;

/**
 * @mixin \think\Model
 */
class UserModel extends BaseModel
{
    protected $name = "user";
    
    use SoftDelete;

    protected $deleteTime = 'delete_time';

    const STATUS_VISIBLE = 1;  //正常

    const STATUS_HIDDEN = 0; //隐藏

    const SUPER_ADMIN = 1; //超级管理员

    /**
     * 一对一关联
     */
    public function roles()
    {
        return $this->hasOne(RoleModel::class,'id','role')->bind(['role_name']);
    }


    /**
     * 数据列表
     */
    public function getUserAll(array $where,int $limit,int $offset):array
    {
        return self::where($where)->with(['roles'])->order('add_time','desc')->limit($offset,$limit)->order('add_time','desc')->select()->toArray();
    }

    /**
     * 数据总量
     */
    public function getUserTotal(array $where):int
    {
        return self::where($where)->count();
    }

    /**
     * 添加时间
     */
    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s',(int)$value);
    }

    /**
     * 根据id 查询是否存在
     */
    public function checkUserId(int $id)
    {
        $data =  self::where('id',$id)->find();
        if(!$data){
            return self::fail('未找到数据');
        }
        return $data;
    }

    /**
     * 验证用户名
     * //mixed
     */
    public function checkUserName(array $where)
    {
        $res = self::where($where)->find();
        if($res){
            return self::fail('管理员已经存在');
        }
    }

    /**
     * 添加管理员
     */
    public function createUser(array $param):object
    {
        return self::create($param);
    }

    /**
     * 更改状态
     */
    public function statusSave():object
    {
        $this->status = $this->status == self::STATUS_VISIBLE ? self::STATUS_HIDDEN : self::STATUS_VISIBLE;
        $this->save();
        return $this;
    }

}

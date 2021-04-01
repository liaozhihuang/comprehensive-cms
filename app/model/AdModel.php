<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class AdModel extends Model
{
    protected $name = "ad";

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    const STATUS_VISIBLE = 1;  //正常

    const STATUS_HIDDEN = 0; //隐藏

    /**
     * 全部数量
     */
    public function getAdAll($where,$offset,$limit)
    {
       return AdModel::where($where)->limit($offset, $limit)->order('id desc')->select();
    }

    /**
     * 数量
     */
    public function getAdTotal($where):int
    {
        return AdModel::where($where)->count();
    }

    /**
     * 验证名称是否存在
     */
    public function checkAdName(array $where)
    {
        $data = self::where($where)->find();
        if($data){
            return self::fail('名称已存在');
        }
    }

    /**
     * 创建
     */
    public function createAd(array $param)
    {
        $data = self::create($param);
        return $data;
    }

    /**
     * 根据🆔id 查询
     */
    public function checkAdId($id)
    {
        $data = self::where('id',$id)->find();
        if(!$data){
            return self::fail('未找到数据');
        }
        return $data;
    }

    /**
     * 创建时间
     */
    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s',(int)$value);
    }

    /**
     * 更改状态
     */
    public function statusSave()
    {
        $this->status = $this->status == self::STATUS_VISIBLE ? self::STATUS_HIDDEN : self::STATUS_VISIBLE;
        $this->save();
        return $this;
    }
}

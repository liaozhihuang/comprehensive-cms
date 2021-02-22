<?php
declare (strict_types = 1);

namespace app\model;

use library\basic\BaseModel;

use think\model\concern\SoftDelete;

/**
 * @mixin \think\Model
 */
class MenuModel extends BaseModel
{
    protected $name = "menu";

    use SoftDelete;

    protected $deleteTime = 'delete_time';

    const STATUS_VISIBLE = 1;  //正常

    const STATUS_HIDDEN = 0; //隐藏


    /**
     * 列表数据
     */
    public function getMenuAll(array $where)
    {
        return self::where($where)->order(['sort'=>'desc','id'])->select()->toArray();
    }

    /**
     * 所有数量
     */
    public function getMenuTotal(array $where):int
    {
        return self::where($where)->count();
    }

    /**
     * 验证名称 创建时
     */
    public function checkMenuName(array $param)
    {
        $data = self::where('menu_name',$param['menu_name'])->whereOr('menu_sign',$param['menu_sign'])->find();
        if($data){
            return self::fail('菜单名称或者标识已存在');
        }
    }

    /**
     * 创建
     */
    public function createMenu(array $param)
    {
        return self::create($param);
    }

    /**
     * 验证id
     */
    public static function checkMenuId(int $id)
    {
        $data = self::where('id',$id)->find();
        if(empty($data)){
            return self::fail('未找到菜单数据');
        }
        return $data;
    }

    /**
     * 更新时 验证名称
     */
    public function checkMenuNameUpdate(array $param)
    {
        $data = self::where('id','<>',$param['id'])->where('menu_name',$param['menu_name'])->find();
        if($data){
            return self::fail('菜单名称或者标识已存在');
        }
    }

    /**
     * 时间
     */
    public function getAddTimeAttr($value)
    {
        return date('Y-m-d H:i:s',(int)$value);
    }

    /**
     * 判断是否有子类
     */
    public function checkMenuSon(int $id)
    {
        $data = self::where('pid','=',$id)->find();
        if($data){
            return self::fail('无法删除，请先删除子菜单');
        }
    }

    /**
     * 获取栏目列表
     */
    public function getMenu():array
    {
        return self::field(['id','menu_name'=>'title','pid'])->order('pid')->select()->toArray();
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

    /**
     * 更改推荐
     */
    public function recommendSave()
    {
        $this->recommend = $this->recommend == self::STATUS_VISIBLE ? self::STATUS_HIDDEN : self::STATUS_VISIBLE;
        $this->save();
        return $this;
    }
}

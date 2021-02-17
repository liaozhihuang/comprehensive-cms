<?php
declare (strict_types = 1);

namespace app\model;

use library\basic\BaseModel;

/**
 * @mixin \think\Model
 */
class NodeModel extends BaseModel
{
    protected $name = "node";

    /**
     * 节点树
     */
    public function getNodeTree():array
    {
        return self::field('id,node_name title,type_id pid,is_menu,style,node_path,reception_path')->append(['key'])->select()->toArray();
    }

    /**
     * 创建
     */
    public function createNode(array $param)
    {
        return self::create($param);
    }

    /**
     * 根据id 查询是否存在
     */
    public function checkNodeId($id)
    {
        $data =  self::where('id',$id)->find();
        if(!$data){
            return self::fail('未找到数据');
        }
        return $data;
    }

    /**
     * 根据id 查询是否子结点存在
     */
    public function checkNodeTid($id)
    {
        $data =  self::where('type_id',$id)->find();
        if($data){
            return self::fail('该节点下有子节点，不可删除');
        }
    }

    /**
     * 获取节点列
     */
    public static function getNodeColumn($node)
    {
        $arr = NodeModel::whereIn('id',$node)->column('node_path');
        if(empty($arr)){
            return self::fail('暂未勾选权限');
        }
        return $arr;
    }

    /**
     * 获取菜单栏
     */
    public static function getMenuColumn():array
    {
        return self::where('is_menu',2)->select()->toArray();
    }

}

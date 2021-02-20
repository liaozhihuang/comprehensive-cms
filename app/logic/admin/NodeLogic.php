<?php
declare (strict_types = 1);

namespace app\logic\admin;

use app\model\NodeModel;
use library\basic\BaseLogic;
use tauthz\facade\Enforcer;
use library\services\NodeBusiness;

class NodeLogic extends BaseLogic
{
    protected $model;


    public function __construct()
    {
        parent::__construct();

        $this->model = new NodeModel();
    }

    /**
     * 列表
     */
    public function getNodeTree()
    {
        $data = NodeBusiness::arrangementTree($this->model->getNodeTree(),false);
        return self::successData($data);
    }

    /**
     * 获取节点
     */
    public function getRoleNodeTree($id)
    {
        $result = $this->model->getNodeTree();
        //获取角色的全部节点
        $data = Enforcer::getPermissionsForUser($id);

        $tree = NodeBusiness::UserNodeTree($result,$data);
        return self::successData($tree);
    }

    /**
     * 创建
     */
    public function createNode(array $param)
    {
        $this->model->createNode($param);   
        return self::successful('添加成功');
    }

    /**
     * 编辑
     */
    public function updateNode(array $param)
    {
        $data = $this->model->checkNodeId($param['id']);
        //::TODO  需要同步 rules节点

        $data->save($param);

        return self::successful('编辑成功');
    }

    /**
     * 删除
     */
    public function delNode($id)
    {
        $data = $this->model->checkNodeId($id);

        $this->model->checkNodeTid($id);

        $data->delete();
        return self::successful('删除成功');
    }


}
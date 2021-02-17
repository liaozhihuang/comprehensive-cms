<?php
declare (strict_types = 1);

namespace app\controller\admin;

use app\BaseController;
use app\validate\NodeValidate;
use app\logic\admin\NodeLogic;

class Node extends BaseController
{
    protected $logic;

    public function initialize()
    {
        parent::initialize();
        $this->logic = new NodeLogic();
    }

    /**
     * 列表
     */
    public function index()
    {
        return $this->logic->getNodeTree();
    }

    /**
     * 获取节点
     */
    public function getNodeTree()
    {
        $id = (int)$this->request->param('id');
        return $this->logic->getRoleNodeTree($id);
    }

    /**
     * 创建
     */
    public function createNode()
    {
        $param = $this->request->param();
        $validate = new NodeValidate();
        if(!$validate->scene('insert')->check($param)){
            /** @final app\services\JsonService  */
            return self::fail($validate->getError());
        }
        return $this->logic->createNode($param);
    }

    /**
     * 编辑
     */
    public function updateNode()
    {
        $param = $this->request->param();

        $validate = new NodeValidate();
        if(!$validate->scene('update')->check($param)){
            /** @final app\services\JsonService  */
            return self::fail($validate->getError());
        }
        return $this->logic->updateNode($param);
    }

    /**
     * 删除节点
     */
    public function delNode()
    {
        $id = $this->request->param('id');
        return $this->logic->delNode($id);
    }

}

<?php
declare (strict_types = 1);

namespace app\controller\admin;

use think\Request;
use app\BaseController;
use app\logic\admin\MenuGroupLogic;
use app\validate\MenuGroupValidate;

class MenuGroup extends BaseController
{
    protected $logic;

    public function initialize()
    {
        parent::initialize();
        $this->logic = new MenuGroupLogic();
    }

    /**
     * 列表
     */
    public function index()
    {
        $param = $this->request->only(['pageSize','pageIndex','search']);
        return $this->logic->getMenuGroupAll($param);
    }

    /**
     * 创建
     */
    public function createMenuGroup()
    {
        $param = $this->request->only(['group_name','group_sign']); 
        
        $validate = new MenuGroupValidate();
        if(!$validate->scene('create')->check($param)){
            return self::fail($validate->getError());
        }
        return $this->logic->createMenuGroup($param);
    }

    /**
     * 更新
     */
    public function updateMenuGroup()
    {
        $param = $this->request->only(['id','group_name']);
        $validate = new MenuGroupValidate();
        if(!$validate->scene('update')->check($param)){
            return self::fail($validate->getError());
        }
        return $this->logic->updateMenuGroup($param);
    }

    /**
     * 删除
     */
    public function delMenuGroup()
    {
        $id = $this->request->param('id');
        return $this->logic->delMenuGroup($id);
    }

    /**
     * 获取数据
     */
    public function getMenuGroup()
    {
        return $this->logic->getMenuGroup();
    }
}

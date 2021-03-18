<?php
declare (strict_types = 1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;
use app\logic\admin\MenuLogic;
use app\validate\MenuValidate;

class Menu extends BaseController
{
    protected $logic;

    public function initialize()
    {
        parent::initialize();
        $this->logic = new MenuLogic();
    }

    /**
     * 列表
     */
    public function index()
    {
        $param = $this->request->only(['search']);
        return $this->logic->getMenuAll($param);
    }


    /**
     * 创建菜单
     */
    public function createMenu()
    {
        $param = $this->request->only(['menu_name','menu_sign','img_url','sort','status','pid','recommend','group_sign']);
        $validate = new MenuValidate();
        if(!$validate->scene('create')->check($param)){
            return self::fail($validate->getError());
        }
        $this->logic->createMenu($param);
    }

     /**
     * 编辑
     */
    public function updateMenu()
    {
        $param = $this->request->only(['id','menu_name','img_url','sort','status','pid','recommend','group_sign']);
    
        $validate = new MenuValidate();
        if(!$validate->scene('update')->check($param)){
            return self::fail($validate->getError());
        }
        $this->logic->updateMenu($param);
    }

    /**
     * 更改状态
     */
    public function statusOperation()
    {
        $id = $this->request->param('id');
        return $this->logic->statusOperation((int)$id);
    }

    /**
     * 更改状态
     */
    public function recommendOperation()
    {
        $id = $this->request->param('id');
        return $this->logic->recommendOperation((int)$id);
    }

    /**
     * 删除
     */
    public function delMenu()
    {
        $id = $this->request->param('id');
        return $this->logic->delMenu((int)$id);
    }

     /**
     * 获取栏目
     */
    public function getMenu()
    {
        return $this->logic->getMenu();
    }

}

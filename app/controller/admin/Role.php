<?php
declare (strict_types = 1);

namespace app\controller\admin;

use think\Request;
use app\BaseController;

use app\logic\admin\RoleLogic;
use app\validate\RoleValidate;

class Role extends BaseController
{
    protected $logic;

    public function initialize()
    {
        parent::initialize();
        $this->logic = new RoleLogic();
    }

    /**
     * 获取角色
     * @date 2020-11-11
     */
    public function getRole()
    {
        return $this->logic->getRole();
    }

    /**
     * 列表
     */
    public function index()
    {
        $param = (array)$this->request->only(['pageSize','pageIndex','search']);
        return $this->logic->getroleAll($param);
    }

    /**
     * 创建
     */
    public function createRole()
    {
        $param = $this->request->only(['role_name']); 
        
        $validate = new RoleValidate();
        if(!$validate->scene('insert')->check($param)){
            return self::fail($validate->getError());
        }
        return $this->logic->createRole($this->request->param('role_name'));
    }

    /**
     * 编辑
     */
    public function updateRole()
    {
        $param = $this->request->only(['id','role_name']);

        $validate = new RoleValidate();
        if(!$validate->scene('update')->check($param)){
            return self::fail($validate->getError());
        }
        return $this->logic->updateRole($param);
    }

    /**
     * 删除
     */
    public function delRole()
    {
        $id = $this->request->param('id');
        return $this->logic->delRole($id);
    }

    /**
     * 分配权限
     */
    public function giveAccess()
    {
        $param = $this->request->only(['id','node']);
        return $this->logic->giveAccess($param);
    }

}

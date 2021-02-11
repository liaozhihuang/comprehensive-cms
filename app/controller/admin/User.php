<?php
declare (strict_types = 1);

namespace app\controller\admin;

use think\Request;
use app\BaseController;
use app\logic\admin\UserLogic;
use app\validate\UserValidate;

class User extends BaseController
{
    protected $logic;

    public function initialize()
    {
        parent::initialize();
        $this->logic = new UserLogic();
    }

    /**
     * 列表
     */
    public function index()
    {
        $param = (array)$this->request->only(['pageSize','pageIndex','search']);
        return $this->logic->getUserAll($param); 
    }

    /**
     * 添加
     */
    public function createUser()
    {
        $param = $this->request->only(['user_name','role','group_id','password','password2','real_name','status']);
        $validate = new UserValidate();
        if(!$validate->scene('insert')->check($param)){
            return self::fail($validate->getError());
        }
        return $this->logic->createUser($param);
    }

    /**
     * 编辑
     */
    public function updateUser()
    {
        $param = $this->request->only(['id','user_name','role','real_name','status','group_id']);
        $validate = new UserValidate();
        if(!$validate->scene('update')->check($param)){
            /** @final JsonService  */
            return self::fail($validate->getError());
        }
        return $this->logic->updateUser($param);
    }

    /**
     * 更改状态
     */
    public function statusOperation()
    {
        $id = (int)$this->request->param('id');
        return $this->logic->statusOperation($id);
    }

    /**
     * 删除
     */
    public function delUser()
    {
        $id = (int)$this->request->param('id');
        return $this->logic->delUser($id);
    }


}

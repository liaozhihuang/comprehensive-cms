<?php

declare(strict_types=1);

namespace app\controller\admin;

use app\BaseController;
use app\logic\admin\AdGroupLogic;
use app\validate\AdGroupValidate;

class AdGroup extends BaseController
{
    protected object $logic;

    public function initialize()
    {
        parent::initialize();
        $this->logic = new AdGroupLogic();
    }

    /**
     * 列表
     */
    public function index()
    {
        $param = $this->request->only(['pageSize', 'pageIndex', 'search']);
        return $this->logic->getAdGroupAll($param);
    }

    /**
     * 创建
     */
    public function createAdGroup()
    {
        $param = $this->request->only(['group_name', 'group_sign']);

        $validate = new AdGroupValidate();
        if (!$validate->scene('create')->check($param)) {
            return self::fail($validate->getError());
        }
        return $this->logic->createAdGroup($param);
    }

    /**
     * 更新
     */
    public function updateAdGroup()
    {
        $param = $this->request->only(['id', 'group_name']);
        $validate = new AdGroupValidate();
        if (!$validate->scene('update')->check($param)) {
            return self::fail($validate->getError());
        }
        return $this->logic->updateAdGroup($param);
    }

    /**
     * 删除
     */
    public function delAdGroup()
    {
        $id = $this->request->param('id');
        return $this->logic->delAdGroup($id);
    }

    /**
     * 获取数据
     */
    public function getAdGroup()
    {
        return $this->logic->getAdGroup();
    }
}

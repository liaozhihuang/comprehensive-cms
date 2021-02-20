<?php
declare (strict_types = 1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;
use app\logic\admin\IndexLogic;

class Index extends BaseController
{
  
    /**
     * 获取栏目
     */
    public function getMenu()
    {
        //获取节点列表
        $logic = new IndexLogic($this->user);
        return $logic->getMenu();
    }





}

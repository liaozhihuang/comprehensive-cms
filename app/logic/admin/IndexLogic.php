<?php
declare (strict_types = 1);

namespace app\logic\admin;

use think\Request;
use library\basic\BaseLogic;
use tauthz\facade\Enforcer;
use app\model\NodeModel;
use library\services\NodeBusiness;

class IndexLogic extends BaseLogic
{
    protected $user;

    public function __construct($user)
    {
        $this->user = $user;
    }

    /**
     * 获取栏目
     */
    public function getMenu()
    {
        //判断是否进入站点  获取站点菜单


        //获取所有菜单
        $menuColumn = NodeModel::getMenuColumn();



        //获取用户节点
        $authArray = Enforcer::getPermissionsForUser($this->user->role);


        //整理菜单
        $data = NodeBusiness::prepareMenu($menuColumn,$authArray,$this->user,true);

        return self::successData($data);
    }



}
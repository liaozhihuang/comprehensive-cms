<?php
declare (strict_types = 1);

namespace app\logic\admin;

use library\basic\BaseLogic;
use app\model\MenuModel;
use library\services\MenuBusiness;

class MenuLogic extends BaseLogic
{
    
    protected $model = "";

    public function __construct()
    {
        parent::__construct();
        $this->model = new MenuModel();
    }

    /**
     * 列表
     */
    public function getMenuAll(array $param)
    {
        $where = [];
        if(!empty($param['search'])){
            $where[] = ['menu_name','like','%'.$param['search'].'%'];
        }
        $data = MenuBusiness::getTree($this->model->getMenuAll($where));
        return self::successData($data);
    }

    /**
     * 创建
     */
    public function createMenu(array $param)
    {
        $this->model->checkMenuName($param);

        // $group = new MenuGroupModel();
        // $group->checkSign($param['group_sign']);

        $param['add_time'] = time();
        $this->model->createMenu($param);

        return self::successful('添加成功');
    }

    /**
     * 编辑
     */
    public function updateMenu(array $param)
    {
        //验证🆔id 是否存在
        $data = $this->model->checkMenuId((int) $param['id']);
        
        $this->model->checkMenuNameUpdate($param);

        // $group = new MenuGroupModel();
        // $group->checkSign($param['group_sign']);

        //::TODO 图片操作

        $data->save($param);

        return self::successful('更新成功');
    }

    /**
     * 更改状态
     */
    public function statusOperation(int $id)
    {
        $data = $this->model->checkMenuId($id);
        $data->statusSave();

        return self::successful('更新成功');
    }

    /**
     * 更改状态
     */
    public function recommendOperation(int $id)
    {
        $data = $this->model->checkMenuId($id);
        $data->recommendSave();

        return self::successful('更新成功');
    }

    /**
     * 删除
     */
    public function delMenu(int $id)
    {
        $data = $this->model->checkMenuId($id);

        //判断是否有子类
        $this->model->checkMenuSon($id);

        $data->delete();
        return self::successful('删除成功');
    }

    /**
     * 获取列表
     */
    public function getMenu()
    {
        $data = $this->model->getMenu();
        return self::successData($data);
    }


}
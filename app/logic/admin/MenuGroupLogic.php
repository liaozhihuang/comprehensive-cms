<?php
declare (strict_types = 1);

namespace app\logic\admin;

use think\Request;
use library\basic\BaseLogic;
use app\model\MenuGroupModel;


class MenuGroupLogic extends BaseLogic
{
    
    protected $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new MenuGroupModel();
    }

    /**
     * 列表
     */
    public function getMenuGroupAll(array $param)
    {
        $where = [];
        if(!empty($param['search'])){
            $where[] = ['group_name','like','%'.$param['search'].'%'];
        }
        
        $limit = (int)$param['pageSize'];
        $offset = (int)($param['pageIndex'] - 1) * $limit;

        $data = $this->model->getMenuGroupAll($where,$offset,$limit);

        $total = $this->model->getMenuGroupTotal($where);
        return self::successData($data,$total);
    }

    /**
     * 创建
     */
    public function createMenuGroup(array $param)
    {
        //验证站点标识
        $this->model->checkMenuGroupSign($param);
        //创建数据
        $param['add_time'] = time();
        $this->model->createMenuGroup($param);
        return self::successful('创建成功');
    }
    
    /**
     * 编辑
     */
    public function updateMenuGroup(array $param)
    {
        //数据是否存在
        $data = $this->model->checkMenuGroupId($param['id']);

        $this->model->checkMenuGroupName($param);

        $data->update_time = time();
        $data->group_name = $param['group_name'];
        $data->save();
        return self::successful('编辑成功');
    }

    /**
     * 删除
     */
    public function delMenuGroup($id)
    {
        //数据是否存在
        $data = $this->model->checkMenuGroupId($id);
        $data->delete();
        return self::successful('删除成功');
    }

    /**
     * 列表
     */
    public function getMenuGroup()
    {
        $data = $this->model->getMenuGroup();
        return self::successData($data);
    }

}
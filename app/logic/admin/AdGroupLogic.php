<?php
declare (strict_types = 1);

namespace app\logic\admin;

use library\basic\BaseLogic;
use app\model\AdGroupModel;


class AdGroupLogic extends BaseLogic
{
    
    protected object $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new AdGroupModel();
    }

    /**
     * 列表
     */
    public function getAdGroupAll(array $param)
    {
        $where = [];
        if(!empty($param['search'])){
            $where[] = ['group_name','like','%'.$param['search'].'%'];
        }
        
        $limit = (int)$param['pageSize'];
        $offset = (int)($param['pageIndex'] - 1) * $limit;

        $data = $this->model->getAdGroupAll($where,$offset,$limit);

        $total = $this->model->getAdGroupTotal($where);
        return self::successData($data,$total);
    }

    /**
     * 创建
     */
    public function createAdGroup(array $param)
    {
        $this->model->checkAdGroup($param);
        //创建数据
        $param['add_time'] = time();
        $this->model->createAdGroup($param);
        return self::successful('添加成功');
    }
    
    /**
     * 编辑
     */
    public function updateAdGroup(array $param)
    {
        //数据是否存在
        $data = $this->model->checkAdGroupId($param['id']);

        $this->model->checkAdGroupName($param);

        $data->update_time = time();
        $data->group_name = $param['group_name'];
        $data->save();
        return self::successful('编辑成功');
    }

    /**
     * 删除
     */
    public function delAdGroup($id)
    {
        //数据是否存在
        $data = $this->model->checkAdGroupId($id);
        $data->delete();
        return self::successful('删除成功');
    }

    /**
     * 列表
     */
    public function getAdGroup()
    {
        $data = $this->model->getAdGroup();
        return self::successData($data);
    }

}
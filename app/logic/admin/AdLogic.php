<?php
declare (strict_types = 1);

namespace app\logic\admin;

use think\Request;
use library\basic\BaseLogic;
use app\model\AdModel;
use lib\services\Jobs;

class AdLogic extends BaseLogic
{
    
    protected $model = "";

    public function __construct()
    {
        parent::__construct();
        $this->model = new AdModel();
    }

    /**
     * 列表
     */
    public static function getAdAll(array $param)
    {
        $where = [];
        if(!empty($param['search'])){
            $where[] = ['ad_name','like','%'.$param['search'].'%'];
        }
        
        $limit = (int)$param['pageSize'];
        $offset = (int)($param['pageIndex'] - 1) * $limit;
        $model = new AdModel();
        $data = $model->getAdAll($where,$offset,$limit);
        $total = $model->getAdTotal($where);
        return self::successData($data,$total);
    }

    /**
     * 创建
     */
    public function createAd(array $param)
    {
        $where[] = ['ad_name','=',$param['ad_name']];
        $this->model->checkAdName($where);

        $param['add_time'] = time();
        $this->model->createAd($param);
        return self::successful('添加成功');
    }

    /**
     * 编辑
     */
    public function updateAd(array $param)
    {
        $data = $this->model->checkAdId($param['id']);

        //验证名字是否存在
        $where[] = ['ad_name','=',$param['ad_name']];
        $where[] = ['id','<>',$param['id']];
        $this->model->checkAdName($where);

        $imgurl = $data->getData('img_url');

        //验证是否同一张图片
        $ret = Jobs::checkImageUrlAlike($imgurl,$param['img_url']);

        $data->status = $param['status'];
        $data->ad_name = $param['ad_name'];
        $data->link = $param['link'];
        $data->group_sign = $param['group_sign'];

        if(!$ret){
            $data->img_url = $param['img_url'];
        }
        $data->save();
        return self::successful('编辑成功');
    }

    /**
     * 删除
     */
    public function delAd($id)
    {
        $data = $this->model->checkAdId($id);
        $data->delete();
        return self::successful('删除成功');
    }

    /**
     * 更改操作
     */
    public function statusOperation($id)
    {
        $data = $this->model->checkAdId($id);
        $data->statusSave();
        return self::successful('修改成功');
    }



}
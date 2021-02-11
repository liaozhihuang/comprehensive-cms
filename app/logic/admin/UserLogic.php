<?php
namespace app\logic\admin;

use library\basic\BaseLogic;
use app\model\UserModel;
use app\model\RoleModel;
use think\facade\Config;
use Exception;

class UserLogic extends BaseLogic
{

    protected $model;


    public function __construct()
    {
        parent::__construct();
        $this->model = new UserModel();
    }
    

    /**
     * 列表
     */
    public function getUserAll(array $param)
    {
        $where = [];
        if(!empty($param['search'])){
            $where[] = ['user_name|mobile','like','%'.$param['search'].'%'];
        }
        
        $limit = (int)$param['pageSize'];
        $offset = (int)($param['pageIndex'] - 1) * $limit;

        //数据
        $rows = $this->model->getUserAll($where,$limit,$offset);
        $total = $this->model->getUserTotal($where);

        return self::successData($rows,$total);
    }

    /**
     * 创建
     */
    public function createUser(array $param)
    {
        RoleModel::checkRoleId((int)$param['role']);

        //验证名字是否存在
        $where[] = ['user_name','=',$param['user_name']];
        $res = $this->model->checkUserName($where);

        $param['password'] = md5($param['password'].config::get('config.password'));

        $param['add_time'] = time();
        $this->model->createUser($param);

        //记录操作日志
        return self::successful('添加成功');
    }

    /**
     * 编辑
     */
    public function updateUser(array $param)
    {
        //判断是否存在
        $user = $this->model->checkUserId((int)$param['id']);

        RoleModel::checkRoleId((int)$param['role']);

        //验证名字是否存在
        $where[] = ['user_name','=',$param['user_name']];
        $where[] = ['id','<>',$param['id']];
        $res = $this->model->checkUserName($where);

        try{
            $user->save($param);
            return self::successful('修改成功');
        }catch(Exception $e){
            return self::fail('修改失败');
        }
    }

    /**
     * 更改状态
     */
    public function statusOperation(int $id)
    {
        $user = $this->model->checkUserId($id);

        $user->statusSave();

        return self::successful('操作成功');
    }


    /**
     * 删除
     */
    public function delUser(int $id)
    {
        $user = $this->model->checkUserId($id);

        $user->delete();

        return self::successful('删除成功');
    }


}
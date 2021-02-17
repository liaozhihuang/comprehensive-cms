<?php
declare (strict_types = 1);

namespace app\logic;

use think\Request;
use library\basic\BaseLogic;
use app\model\RoleModel;
use app\model\NodeModel;
use app\model\RulesModel;
use tauthz\facade\Enforcer;

class RoleLogic extends BaseLogic
{
    protected $model = "";
    protected $user;

    public function __construct($user)
    {
        parent::__construct();
        $this->model = new RoleModel();
        $this->user = $user;
    }

    /**
     * 获取全部角色
     */
    public function getRole()
    {
        $data = $this->model->getRole();
        return self::successData($data);
    }

    /**
     * 列表
     */
    public function getroleAll(array $param)
    {
        $where = [];
        if(!empty($param['search'])){
            $where[] = ['role_name','like','%'.$param['search'].'%'];
        }
        
        $limit = (int)$param['pageSize'];
        $offset = (int)($param['pageIndex'] - 1) * $limit;

        //数据
        $rows = $this->model->getRoleAll($where,$limit,$offset);
        $total = $this->model->getRoleTotal($where);

        //成功返回
        return self::successData($rows,$total);
    }

    /**
     * 添加
     */
    public function createRole(string $role_name)
    {
        $where[] = ['role_name','=',$role_name];
        $this->model->checkRoleName($where);

        $this->model->role_name = $role_name;
        $this->model->add_time = time();
        $res = $this->model->save();

        $event['content'] = '添加角色';
        $event['type'] = self::LOG_CREATE_TYPE;
        $event['info'] = 'role-'.$this->model->id;
        $event['user_id'] = ''.$this->user->id;
        event('RecordUserLogAfter',$event);

        return self::successful('创建成功');
    }

    /**
     * 编辑
     */
    public function updateRole(array $param)
    {
        //判断数据是否存在
        $data = $this->model->checkRoleId($param['id']);

        //验证名称是否存在
        $where[] = ['role_name','=',$param['role_name']];
        $where[] = ['id','<>',$param['id']];
        $this->model->checkRoleName($where);

        $data->role_name = $param['role_name'];
        $data->save();

        $event['content'] = '编辑角色';
        $event['type'] = self::LOG_CREATE_TYPE;
        $event['info'] = 'role-'.$data->id;
        $event['user_id'] = ''.$this->user->id;
        event('RecordUserLogAfter',$event);


        return self::successful('修改成功');
    }

    /**
     * 删除
     */
    public function delRole($id)
    {
        $data = $this->model->checkRoleId($id);
        $data->delete();
        return self::successful('删除成功');
    }

    /**
     * 分配权限
     */
    public function giveAccess($param)
    {
        $nodeArray = NodeModel::getNodeColumn($param['node']);

        //获取角色的全部节点 作为暂存
        $authArray = Enforcer::getPermissionsForUser((string)$param['id']);

        //删除全部节点
        Enforcer::deletePermissionsForUser((string)$param['id']);

        $node = [];
        foreach($nodeArray as $k=>$v){
            if(empty($v)){
                continue;
            }
            $node[$k]['ptype'] = 'p';
            $node[$k]['v0'] = $param['id'];
            $node[$k]['v1'] = $v;
            $node[$k]['v2'] = 'api';
        }
        RulesModel::createRulesAll(array_values($node));

        $event['content'] = '分配权限';
        $event['type'] = self::LOG_INFO_TYPE;
        $event['info'] = 'role-'.$param['id'];
        $event['user_id'] = ''.$this->user->id;
        event('RecordUserLogAfter',$event);

        return self::successful('操作成功');
    }



}

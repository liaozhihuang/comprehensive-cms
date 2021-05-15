<?php
declare (strict_types = 1);

namespace app\controller\admin;

use think\Request;
use app\BaseController;
use app\logic\admin\AdLogic;
use app\validate\AdValidate;

class Ad extends BaseController
{
    protected $logic;

    public function initialize()
    {
        parent::initialize();
        $this->logic = new AdLogic();
    }

    /**
     * 列表
     */
    public function index()
    {
        $param = $this->request->only(['pageSize','pageIndex','search']);
        return $this->logic->getAdAll($param);
    }

    /**
     * 添加轮播图
     */
    public function createAd()
    {
        $param = (array)$this->request->param();
        $validate = new AdValidate();
        if(!$validate->scene('create')->check($param)){
            return json(['code'=>0,'msg'=>$validate->getError()]);  
        }
        return $this->logic->createAd($param);
    }

     /**
     * 编辑banner
     * @author liao
     * @date 2020-9-24
     */
    public function updateAd()
    {
        $param = $this->request->param();
        $validate = new AdValidate();
        if(!$validate->scene('update')->check($param)){
            return json(['code'=>0,'msg'=>$validate->getError()]);
        }
        return$this->logic->updateAd($param); 
    }

    /**
     * 删除
     */
    public function delAd()
    {
        $id = $this->request->param('id');
        return $this->logic->delAd($id);
    }

    /**
     * 更改状态
     */
    public function statusOperation()
    {
        $id = $this->request->param('id');
        return $this->logic->statusOperation($id);
    }


}

<?php
declare (strict_types = 1);

namespace app\controller\admin;

use app\BaseController;
use think\Request;
use app\logic\admin\NoticeLogic;

class Notice extends BaseController
{
    protected object $logic;

    public function initialize()
    {
        parent::initialize();
        $this->logic = new NoticeLogic();
    }

    /**
     * 列表
     * @param Request $request
     */
    public function index(Request $request)
    {

        //搜索 类型
        $param = $request->only(['pageSize','pageIndex','search']);
        return $this->logic->getNoticeList($param);
    }

}

<?php
declare(strict_types=1);

namespace app\logic\admin;

use app\model\NoticeModel;
use library\basic\BaseLogic;

class NoticeLogic extends BaseLogic
{
    protected $model = "";

    public function __construct()
    {
        parent::__construct();
        $this->model = new NoticeModel();
    }


    /**
     * 列表
     * @param array $param
     * @return void
     */
    public function getNoticeList(array $param)
    {
    
    }


}


<?php
declare (strict_types = 1);

namespace app\model;

use think\Model;

/**
 * @mixin \think\Model
 */
class RulesModel extends Model
{
    protected $name = "rules";

    /**
     * 批量添加节点
     */
    public static function createRulesAll(array $insert)
    {
        $model = new self;
        $model->saveAll($insert);
    }

}

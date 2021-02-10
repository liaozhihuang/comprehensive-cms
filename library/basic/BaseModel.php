<?php
declare (strict_types = 1);

namespace library\basic;

use think\facade\Db;
use think\Model;
use library\services\JsonService;


class BaseModel extends Model
{
    use JsonService;
    
    const LOG_CREATE_TYPE = 'create';
    
    const LOG_UPDATE_TYPE = 'update';
    
    const LOG_DELETE_TYPE = 'delete';
    
    const LOG_INFO_TYPE = 'info';

    /**
     * 开启事务
     */
    public static function beginTrans()
    {
        Db::startTrans();
    }

    /**
     * 提交事务
     */
    public static function commitTrans()
    {
        Db::commit();
    }

    /**
     * 关闭事务
     */
    public static function rollbackTrans()
    {
        Db::rollback();
    }

    /**
     * 根据结果提交滚回事务
     * @param $res
     */
    public static function checkTrans($res)
    {
        if($res){
            self::commitTrans();
        }else{
            self::rollbackTrans();
        }
    }



}

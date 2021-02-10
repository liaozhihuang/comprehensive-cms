<?php
declare (strict_types = 1);

namespace library\basic;

use think\App;
use think\exception\ValidateException;
use think\Validate;
use library\services\JsonService;
use app\model\UserModel;

abstract class BaseLogic
{
    use JsonService;


    const LOG_CREATE_TYPE = 'create';
    
    const LOG_UPDATE_TYPE = 'update';
    
    const LOG_DELETE_TYPE = 'delete';
    
    const LOG_INFO_TYPE = 'info';

}

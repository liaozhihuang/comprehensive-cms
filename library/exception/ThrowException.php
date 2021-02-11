<?php
declare (strict_types = 1);

namespace library\exception;

class ThrowException extends BaseException
{
    //http 状态码 
	public $code = 401; 
    //错误具体消息 
    public $msg = "位置错误"; 
    //自定义错误码 
    public $errorCode = 10000; 

}
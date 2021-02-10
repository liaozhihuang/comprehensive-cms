<?php
declare (strict_types = 1);

namespace app\exception;

use think\Exception;

class BaseException extends Exception 
{ 
	 //http 状态码 
	 public $code = 500; 
	//错误具体消息 
	 public $msg = "服务器出错"; 
	//自定义错误码 
    public $errorCode = 999; 

    //构造函数用于接收传入的异常信息，并初始化类中的属性 
    public function __construct($params=[]) 
    {
        if (!is_array($params)) { 
            return; 
        } 
        if (array_key_exists('code', $params)) {
            $this->code = $params['code']; 
        } 
        if (array_key_exists('msg', $params)) {
            $this->msg = $params['msg']; 
        } 
        if (array_key_exists('errorCode', $params)) {
            $this->errorCode = $params['errorCode']; 
        } 
    } 



}

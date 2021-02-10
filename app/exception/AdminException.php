<?php

namespace app\exception;

use think\exception\Handle;
use think\exception\ValidateException;
use think\Response;
use Throwable;
use think\facade\Log;

/**
 * 后台异常处理
 *
 * Class AdminException
 * @package app\exception
 */
class AdminException extends Handle
{

    // http 错误码 
	private $code; 
	// 自定义异常信息 
	private $msg; 
	// 自定义错误码 
	private $errorCode;

    public function render($request, Throwable $e): Response
    {
        // 参数验证错误
        if ($e instanceof ValidateException){
            return app('json')->make(422, $e->getError());
        }
        if ($e instanceof \Exception && request()->isAjax()) {
            return app('json')->fail(['code' => $e->getCode(), 'message' => $e->getMessage(), 'file' => $e->getFile()]);
        }
        if ($e instanceof BaseException){
            $this->code = $e->code; 
		    $this->msg = $e->msg; 
            $this->errorCode = $e->errorCode;
            
            $result = [
                'code'=>$this->code,
                'msg' => $this->msg,
                'errorCode' => $this->errorCode,
             ];
            return json($result);
        }

        return parent::render($request, $e);
    }
}
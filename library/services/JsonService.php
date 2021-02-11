<?php
declare (strict_types = 1);

namespace library\services;

trait JsonService
{
    private static $SUCCESSFUL_DEFAULT_MSG = 'success';

    private static $FAIL_DEFAULT_MSG = 'error';

    private static $SUCCESS_CODE = 1;

    private static $ERROR_CODE = 0;

    /**
     * 返回
     */
    public static function result($code, $msg = '', $data = [] , $count = 0)
    {
        exit(json_encode(compact('code', 'msg', 'data','count')));
    }

    /**
     * 返回
     */
    public static function retful($code = '',$msg = '' , $data = [])
    {
        exit(json_encode(compact('code', 'msg', 'data')));
    }

    /**
     * 成功
     */
    public static function successful($msg = '', $data = [], $code ='')
    {
        $msg = $msg ?: self::$SUCCESSFUL_DEFAULT_MSG;

        $code = $code ?:  self::$SUCCESS_CODE;

        return self::retful($code, $msg, $data);
    }

    /**
     * 失败
     */
    public static function fail($msg = '', $data = [], $code = '')
    {
        $msg = $msg ?: self::$FAIL_DEFAULT_MSG;
        $code = $code ?:  self::$ERROR_CODE;
        return self::retful($code, $msg, $data);
    }

    /**
     * 成功返回数据
     */
    public static function successData( $data = [] , $count = 0, $msg = '')
    {
        $msg = $msg ?: self::$SUCCESSFUL_DEFAULT_MSG;  
        return self::result(self::$SUCCESS_CODE, $msg, $data, $count);
    }

    /** 
     * 设置返回数据
     * @param int $code 响应code
     * @param string $msg 提示语
     * @param array $data 返回数据
     * @return array
     * */
    public static function returnData($code , $msg = '',$data = [])
    {
        return compact('code', 'msg', 'data');
    }

}
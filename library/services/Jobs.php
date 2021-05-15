<?php
declare (strict_types = 1);

namespace lib\services;

class Jobs 
{
    /**
     * 验证两张图片是否相同
     */
    public static function checkImageUrlAlike($original,$now):bool
    {
        if(empty($original)){
            return false;
        }

        $original_img = substr($original,strripos($original,"/")+1);

        $now_img = substr($now,strripos($now,"/")+1);

        if($original_img == $now_img){
            return true;
        }else{
            return false;
        }
    }





}


<?php
declare (strict_types = 1);

namespace app\controller\admin;

use app\BaseController;
use think\facade\Filesystem;
use think\exception\ValidateException;

class Upload extends BaseController
{
    /**
     * 上传图片 单图
     */
    public function uploadImageSingle()
    {
        $file = $this->request->file('file');
        $savename = '';
        try{
            validate(['imgFile'=>[
                'fileSize' => 20480,
                'fileExt' => 'jpg,jpeg,png,bmp,gif',
                'fileMime' => 'image/jpeg,image/png,image/gif',
                ]])->check(['file'=>$file]);
            $savename = Filesystem::disk('public')->putFile( 'images', $file);  //文件上传
        }catch(ValidateException $e){
            return json(['code'=>0,'msg'=>$e->getMessage()]);
        }
        $url = "/storage/".$savename;
        return json(['code'=>1,'msg'=>'success','data'=>$url]);
    }
}

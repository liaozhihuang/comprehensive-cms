<?php
declare (strict_types = 1);

namespace app\validate;

use think\Validate;

class AdValidate extends Validate
{
    protected $rule = [
        'id'    =>  'require|integer',
        'ad_name' =>  'require|max:40',
        'group_id'    =>  'require', //标识
        'img_url'  =>  'require',
        'status' =>  'require|integer',
        'link'  =>  'url',
    ];
    protected $message = [
        'id.require'    =>  '主键id必须',
        'id.integer'    =>  '主键id参数类型错误',

        'ad_name.require' =>  'banner名称必须',
        'ad_name.max' =>  'banner名称长度不符合',

        'group_id.require' =>  '广告组必须',

        'link.url'  =>  '链接格式错误',

        'img_url.require'  =>  '图片必须',

        'status.require'  =>  '状态必须',
        'status.integer'   =>  '状态参数不正确',
    ];

    protected $scene = [
        'update'  =>  ['id','ad_name','link','img_url','status','group_id'],
        'create'    =>  ['ad_name','link','img_url','status','group_id'],
        'switch_status' =>  ['id','status'],
    ];


}

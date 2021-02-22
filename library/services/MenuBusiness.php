<?php
declare (strict_types = 1);

namespace library\services;

/* 菜单业务 */
class MenuBusiness 
{
    /**
     * 整理数据
     */
    public static function getTree(array $data)
    {
        $parent = []; //父类
        $child = []; //子类
        foreach($data as $key=>$val){
            $parent[$val['id']] = $val;
            // $parent[$val['id']]['children']= [];
        }
        unset($data);
        //查找子类
        foreach($parent as $k=>$v){
            if($v['pid'] !=0 ){
                $parent[$v['pid']]['children'][] = &$parent[$k];
            }
        }
        //过滤杂质
        foreach($parent as $key=>$val){
            if($val['pid'] == 0){
                $child[] = $val;
            }
        }
        unset($parent);
        return $child;

    }


    /**
     * 整理数据
     */
    public static function getTreeColumn(array $data,$arr = [])
    {

        $parent = []; //父类
        $child = []; //子类
        foreach($data as $key=>$val){
            $id = $val['pid'] == 0 ? $val['id'] : $val['pid'];
            if(in_array($id,$arr)){
                $arr[] = $val['id'];
                $parent[$val['id']] = $val;
            }
        }
 
        unset($data);
        //查找子类
        foreach($parent as $k=>$v){
            if($v['pid'] !=0 ){
                $parent[$v['pid']]['children'][] = &$parent[$k];
            }
        }
        //过滤杂质
        foreach($parent as $key=>$val){
            if($val['pid'] == 0){
                $child[] = $val;
            }
        }

        unset($parent);
        return $child[0]['children'] ?? [];
    }








    /**
     * 整理数据
     */
    public static function getTreeLevel(array $data):array
    {
        $arr = [];
        foreach($data as $k=>$v){
            $arr[$k]['id'] = $v['id'];
            if($v['delimiter'] != ''){
                $arr[$k]['title'] = "   |".$v['delimiter'].$v['title'];
            }else{
                $arr[$k]['title'] = $v['title'];
            }
        }
        return $arr;
    }

    /**
     * 递归划分等级
     */
    public static function TopLevel($cate,$ids=[],$delimiter = '———', $pid = 0, $level = 0):array
    {
        $arr = array();
		foreach ($cate as $v) {
			if ($v['pid'] == $pid) {
				$v['level'] = $level + 1;
                $v['delimiter'] = str_repeat($delimiter, $level);
                if(in_array($v['id'],$ids) ||empty($ids)){
                    $arr[] = $v;
                }
                $arr = array_merge($arr, self::TopLevel($cate,$ids,$delimiter, $v['id'], $v['level']));
			}
		}
		return $arr;
    }


}


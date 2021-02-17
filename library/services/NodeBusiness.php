<?php
declare (strict_types = 1);

namespace library\services;

/* 节点业务 */
class NodeBusiness 
{

    /**
     * 整理菜单栏
     * @param $param  全部节点 
     * @param $data 用户节点
     * @param $user 用户
     */
    public static function prepareMenu(array $param,array $data, $user,$bool=true):array
    {
        $authArray = [];
        foreach($data as $k=>$v){
            $authArray[$k] = $v[1];
        }
        $parent = []; //父类
        $child = []; //子类
        //判断顶级还是子类
        foreach($param as $k=>$v){
            if(!in_array($v['node_path'], $authArray) && $user->role !=1 && $bool){
                continue;
            }
            if($v['type_id'] == 0){  //0表示顶级节点 
                $parent[] = $v;
            }else{
                $child[] = $v;
            }
        }


        //组装数据
        foreach($parent as $key=>$val){
            foreach($child as $k=>$v){
                if($v['type_id'] == $val['id']){
                    $parent[$key]['child'][] = $v;
                }
            }
        }

        //转成前台可用的数据
        $ret = [];
        foreach($parent as $k=>$v){
            $arr = [];
            $arr['icon'] = $v['style'];
            $arr['index'] = isset($v['child']) ? $k :$v['reception_path'];
            $arr['title'] = $v['node_name'];
            $arr['id'] = $v['id'];

            if(isset($v['child'])){
                foreach($v['child'] as $key=>$val){
                    $arr['subs'][$key]['index'] = $val['reception_path'];
                    $arr['subs'][$key]['title'] = $val['node_name'];
                    $arr['subs'][$key]['id'] = $val['id'];
                    $arr['subs'][$key]['tid'] = $val['type_id'];
                }
            }
            $ret[$k] = $arr;
        }
 
        unset($child);
        return $ret;
    }

    /**
     * 处理用户节点,和所有节点
     * @param $data 所有节点
     * @param $arr 用户节点
     */
    public static function UserNodeTree($data,$arr):array
    {
        $authArray = [];
        foreach($arr as $k=>$v){
            $authArray[$k] = $v[1];
        }
        $parent = []; //父类
        $child = []; //子类
        $checked = [];
        foreach($data as $key=>&$val){
           if(!empty($authArray) && in_array($val['node_path'], $authArray)){
                $checked[] = $val['id'];
            }
            $data[$key]['key'] = $val['id'];  
            $parent[$val['id']] = $val;
            $parent[$val['id']]['children']= [];
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
        $ret['nodeAll'] = $child;
        $ret['checked'] = $checked;
        return $ret;
    }

    /**
     * 整理成节点树数据 
     * @data 所有节点
     */
    public static function arrangementTree($data,$bool = true): array
    {
        $parent = []; //父类
        $child = []; //子类
        foreach($data as $key=>&$val){
            $data[$key]['key'] = $val['id'];   
            $parent[$val['id']] = $val;
            $parent[$val['id']]['children']= [];
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


}


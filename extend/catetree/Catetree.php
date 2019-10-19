<?php
namespace catetree;
class Catetree {
    public function catetree($cateRes){
        return $this->sort($cateRes);
    }

    public function sort($cateRes,$pid=0,$level=0){
        static $arr=array();
        foreach ($cateRes as $k => $v) {
            if($v['pid']==$pid){
                $v['level']=$level;
                $arr[]=$v;
                $this->sort($cateRes,$v['id'],$level+1);
            }
        }
        return $arr;
    }


    //获取子所有id
    public function childrenids($cateid,$obj){
        $data=$obj->field('id,pid,name')->order('listorder desc')->select();
        return $this->_childrenids($data,$cateid,TRUE);
    }

    // 获取下一级子类的id
    public function sonids($cateid,$obj){
        $data=$obj->field('id,pid')->order('listorder desc')->select();
        return $this->_getSonids($data,$cateid,TRUE);
    }

    public function parentids($cateid,$obj,$addField){
        $Field = 'pid,id,name';
        $Field .= ',' . $addField;
        $curData = $obj->where('id',$cateid)->field($Field)->find();
        $data=$obj->field('id,pid,name')->select();
        $result = $this->_parentids($data,$curData['pid'],TRUE);
        array_push($result,$curData);
        return $result;
    }

    private function _parentids($data,$pid,$clear=FALSE){
        static $arr = [];
        if($clear){
            $arr = [];
        }
        foreach ($data as $k => $v) {
            if($v['id']==$pid){
                $arr[]=$v;
                $this->_parentids($data,$v['pid'],false);
            }
        }
        return array_reverse($arr);
    }

    // 获取整个分类
    public function cateData($obj, $fieldStr='',$times, $pid=0){
        $field = "id,pid,name";
        $field .= ',' . $fieldStr;
        $arr = $obj->field($field)->order(['listorder'=> 'desc','id' => 'desc'])->select();
        // 生成无限极分类树
        return $this->hTree($arr, $pid, $times);
    }

    public function hTree($arr,$pid=0,$times, $level=0){
        foreach($arr as $k => $v){
            if($v['pid']===$pid){
                $data['_'.$v['id']]=$v;
                if(isset($times)){
                    $times = intval($times);
                    if($times > $level) {
                        $data['_'.$v['id']]['sub']=self::hTree($arr,$v['id'],$times,$level+1);
                    }else{
                        $data['_'.$v['id']]['sub'] = null;
                    };
                }else{
                    $data['_'.$v['id']]['sub']=self::hTree($arr,$v['id']);
                }

            }
        }
        return isset($data)?$data:null;
    }

    private function _childrenData($data,$cateid,$clear=FALSE){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach ($data as $k => $v) {
            if($v['pid']==$cateid){
                $this->_childrenData($data,$v['id']);
                $arr[]=$v;
            }
        }
        return $arr;
    }


    private function _childrenids($data,$cateid,$clear=FALSE){
        static $arr=array();
        if($clear){
          $arr=array();
        }
        foreach ($data as $k => $v) {
            if($v['pid']==$cateid){
                $arr[]=$v['id'];
                $this->_childrenids($data,$v['id']);
            }
        }
        return $arr;
    }





    private function _getSonids($data,$cateid,$clear=FALSE){
        static $arr=array();
        if($clear){
            $arr=array();
        }
        foreach ($data as $k => $v) {
            if($v['pid']==$cateid){
                $arr[]=$v['id'];
            }
        }
        return $arr;
    }

    //处理栏目排序
    public function cateSort($data,$obj){
        foreach ($data as $k => $v) {
            $obj->update(['id'=>$k,'sort'=>$v]);
        }
    }

    //处理批量删除
    public function pdel($cateids,$obj){
        foreach ($cateids as $k => $v) {
            $childrenidsarr[]=$this->childrenids($v,$obj);
            $childrenidsarr[]=(int)$v;

        }

        $_childrenidsarr=array();
        foreach ($childrenidsarr as $k => $v) {
            if(is_array($v)){
                foreach ($v as $k1 => $v1) {
                   $_childrenidsarr[]=$v1;
                }
            }else{
                $_childrenidsarr[]=$v;
            }
        }
        $allson = $_childrenidsarr=array_unique($_childrenidsarr);
        return $allson;
    }


    // 树结构
    public function generateTree($data){
        // 构造数据
        $items = [];
        foreach ($data as $val){
            $items[$val['id']] = $val;
        }

        // 遍历数据，生成数据结构
        $tree = [];
        foreach ($items as $key => $val){
            if(isset($items[$val['pid']])){
                $items[$val['pid']]['son'][] = &$items[$key];
            }else{
                $tree[] = &$items[$key];
            }
        }
        return $tree;
    }


}
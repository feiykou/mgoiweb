<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 15:24
 */

namespace app\common\model;


use catetree\Catetree;
use think\Model;

class Category extends Model
{

    protected function getMainImgUrlAttr($val,$data){
        return $this->handleImgUrl($val);
    }

    protected function getMobileImgsUrlAttr($val,$data){
        return $this->handleImgUrl($val);
    }

    private function handleImgUrl($val){
        $val = str_replace('\\','/',$val);
        $arr = explode(';',$val);
        foreach ($arr as &$item){
            $item = config('APISetting.img_prefix').$item;
        }
        return $arr;
    }

    // 获取分类下的所有数据
    public function getAllCate(){
        $data = [
            'status' => 1
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = self::where($data)->order($order)->select();
        return $result;
    }

    // 获取列表数据，并获取父类name
    public function getIndexCateData(){
        $cateData = self::alias('a1')
            ->field('a1.*,a2.name as pname')
            ->where(['a1.status'=>1])
            ->order([
                'a1.listorder'=>'desc',
                'a1.id' => 'desc'
            ])
            ->join('category a2','a1.pid=a2.id','left')
            ->paginate();
        return $cateData;
    }

    // 获取顶级分类，如果有$id并且是顶级分类，则排除
    public function getNormalFirstCate($id=0){
        $data = [
            ['status','=',1],
            ['pid','=',0],
            ['id','neq',$id]
        ];

        $order = [
            'id' => 'desc'
        ];

        return $this->where($data)
            ->order($order)
            ->select();
    }

    public function getColumnCate(){
        $data = [
            'status' => 1
        ];
        $columnData = $this->where($data)->select();
        $columnSortData = sortData($columnData);
        return $columnSortData;
    }

    // 获取当前栏目下的所有子分类
    public function getFirstCate($pid = 0){
        $data = [
            ['pid', '=', $pid],
            ['status', 'neq', -1]
        ];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc'
        ];
        $result = $this->where($data)
                    ->order($order)
                    ->paginate();
        return $result;
    }

    // 判断是否存在同名
    public function is_unique($name="",$id=0){
        $data = [
            'status'    => ['neq',-1],
            'id'        => ['neq',$id],
            'name'      => ['=',$name]
        ];
        $result = $this->where($data)->find();
        return $result;
    }


    public function getAllColumn(){
        $data = [
            'status'    =>  1,
            'pid' =>  0,
        ];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc'
        ];
        $column = db("category")->where($data)->order($order)->select();
        foreach ($column as $key => $v){
            $children = db("category")->where([
                    'pid'=>$v['id'],
                    'status' => 1
                ])->select();
                if($children){
                    $column[$key]['children'] = $children;
                }else{
                    $column[$key]['children'] = 0;
            }
        }
        return $column;
    }


    public static function getCateJson($field='',$times,$pid=0){
        $data = self::_cateData($field, $times,$pid);
        return $data;
    }


    private static function _cateData($fieldStr='',$times, $pid=0){
        $cateTree = new Catetree();
        $field = "id,pid,name";
        $field .= ',' . $fieldStr;
        $arr = self::field($field)
            ->order(['listorder'=> 'desc','id' => 'desc'])
            ->select()
            ->each(function($data){
            });
        // 生成无限极分类树
        return $cateTree->hTree($arr, $pid, $times);
    }





}
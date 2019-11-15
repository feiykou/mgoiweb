<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/7
 * Time: 11:03
 */

namespace app\common\model;


use catetree\Catetree;
use think\Model;

class ColumnCate extends Model
{
    protected $hidden = [
        'update_time'
    ];

    protected function getImgUrlAttr($val,$data){
        return $this->handleImgUrl($val);
    }

    private function handleImgUrl($val){
        $val = str_replace('\\','/',$val);
        $arr = explode(';',$val);
        foreach ($arr as &$item){
            if(!$item) break;
            $item = config('APISetting.img_prefix').$item;
        }
        return $arr;
    }

    public function column(){
        return $this->hasMany('column','cate_id','id');
    }

    public function getAllCateData(){
        $cateData = self::alias('a1')
            ->field('a1.*,a2.name as pname')
            ->where(['a1.status'=>1])
            ->order([
                'a1.listorder'=>'desc',
                'a1.id' => 'desc'
            ])
            ->join('column_cate a2','a1.pid=a2.id','left')
            ->paginate();
        return $cateData;
    }

    /**
     * 获取当前id的父级元素和其他元素
     * @param $id
     * @return mixed
     */
    public static function getCateData($id=-1){
        $model = new self();
        $data = [
            'status' => 1
        ];
        $ModelDatas = $model->where($data)->select();

        $ids = "";
        if($id != -1){
            $sonDatas = sortData($ModelDatas,$id);
            foreach ($sonDatas as $key=>$value){
                $ids.= $value['id'].',';
            }
            $ids .= trim($id);
            $parentDept = $model->where([
                ['id','not in',$ids],
                ['status','=',1]
            ])->select();
        }else{
            $parentDept = $ModelDatas;
        }
        // 排除id字符串的两种方式
        if(!$parentDept){
            return false;
        }else{
            // 对部门进行重新排序
            $parentSortDept = sortData($parentDept);
        }
        return $parentSortDept;
    }

    public function getChildCateIds($id=-1){
        $data = [
            'status' => 1
        ];
        $ModelDatas = self::where($data)->select();
        $ids = "";
        if($id != -1){
            $sonDatas = sortData($ModelDatas,$id);
            foreach ($sonDatas as $key=>$value){
                $ids.= $value['id'].',';
            }
            $ids .= trim($id);
        }
        return $ids;
    }

    public function getCateById($id=0){
        $data = [
            'status' => 1,
            'id'     => $id
        ];
        $modelDatas = self::where($data)->find();
        return $modelDatas;
    }

    // 判断是否存在同名
    public function is_unique($name="",$id=0){
        $data = [
            ['status','=',1],
            ['id','neq',$id],
            ['name','=',$name]
        ];
        $result = $this->where($data)->find();
        return $result;
    }

    // 判断当前id是否存在子类
    public function is_child($id=-1){
        if(is_numeric($id)){
            $id = [$id];
        }
        $data = [
            ['pid','in',$id],
            ['status','=',1]
        ];
        $result = self::where($data)->find();

        return $result;
    }

    public function delSelChild($idArr=[]){
        $data = [
            ['id','in',$idArr]
        ];
        $result = $this->where($data)->update(['status'=>0]);
        return $result;
    }

    public function is_exist_pro($id){
        if(is_numeric($id)){
            $id = [$id];
        }
        $data = [
            ['status','neq',-1],
            ['pro_cate_id','in',$id]
        ];
        $result = \model('product')->where($data)->find();
        return $result;
    }


    /**
     * 获取api数据
     */
    // 获取当前分类的所有子类id，包括当前id
    public static function getAllCateById($cateId){
        $cateTree = new Catetree();
        $arr = $cateTree->childrenids($cateId, new self());
        array_push($arr,$cateId);
        return $arr;
    }

    /**
     * 获取当前分类下的所有子分类
     * @url
     * @http
     * @param $cateId
     * @return array
     */
//    public static function getAllSonData($cateId){
//        $cateTree = new Catetree();
//        $ids = $cateTree->childrenids($cateId, new self());
//        $data = [];
//        if(count($ids) > 0){
//            $data = self::_getSelCate($ids)->toArray();
//            $data = $cateTree->generateTree($data);
//        }
//        return $data;
//    }

    /*
     * 获取分类信息  --- 多个分类
     */
    private static function _getSelCate($ids=[],$fieldStr=''){
        $field = 'id,pid,name,img_url';
        if($fieldStr) $field .= $fieldStr;
        $data = self::where('status','=',1)
            ->field($field)
            ->order([
                'listorder' => 'desc',
                'id' => 'desc'
            ])->select($ids);
        return $data;
    }











    public static function getIndexCateProduct(){
        $data = [
            'status'    =>  1,
            'pid' =>  0,
        ];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc'
        ];
        $column =self::where($data)->order($order)->field('id,name')->select();
        $cateData = [];
        foreach ($column as $key => $val){
            $tempData = [];
            $result = Product::getProductByCate($val['id']);
            if($result){
                $tempData['data'] = $result;
                $tempData['name'] = $val['name'];
                array_push($cateData,$tempData);
            }

        }
        return $cateData;
    }

    public static function getTopCate(){
        $data = [
            'status'    =>  1,
            'pid' =>  0,
        ];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc'
        ];
        $column =self::where($data)->order($order)->field('id,name')->select();
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


    public static function getColumnList($cate_id){
        $data = [
            'pid' => $cate_id,
            'status' => 1
        ];
        $result = self::where($data)
            ->order('listorder desc')
            ->hidden(['pid','listorder','status','description','create_time','update_time'])
            ->with(['column'=>function($query){
                $query->field('id,name,introduce,main_img_url,mobile_imgs_url,cate_id,create_time');
            }])
            ->select();
        return $result;
    }

    /**
     *  获取面包屑
     */
    public static function getCrumb($cateId=0){
        $cateTree = new Catetree();
        $parentArr = $cateTree->parentids($cateId, new self(),'img_url');
        return $parentArr;
    }

    /**
     * 获取所有分类
     */
    public static function getAllCate() {
        $data = [
            'status' => 1
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = self::where($data)
            ->order($order)
            ->withCount(['column' => function($query) {
                return $query->where('status',1);
            }])
            ->visible(['id','name','column_count'])
            ->select();
        return $result;
    }

    // 获取分类下的所有专栏
    public static function getColumnAndCateByCate($id=0)
    {
        $data = [
            'status' => 1,
            'id' => $id
        ];
        $result = self::where($data)
            ->with(['column' => function($query){
                $query->where(['status' => 1])->order('listorder desc');
            }])
            ->visible(['id','name','column.introduce','column.id','column.name','column.main_img_url','column.mobile_imgs_url'])
            ->find();
        return $result;
    }

    // 获取首页推荐分类及专栏
    public static function getRescCateAndColumn($resc_id, $simple=1)
    {
        $data = [
            'status' => 1
        ];

        $with = [];
        $visiable = ['id','name'];
        if(intval($simple) === 0){
            $with = ['column' => function($query){
                $query->where(['status' => 1])->order('listorder desc')->limit(4);
            }];
            $visiable = array_merge($visiable, ['column.introduce','column.id','column.name','column.main_img_url','column.mobile_imgs_url']);
        }

        $result = self::where($data)
            ->where('','exp',"find_in_set($resc_id,attributes)")
            ->with($with)
            ->visible($visiable)
            ->select();
        return $result;
    }
}
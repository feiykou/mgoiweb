<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/7
 * Time: 11:03
 */

namespace app\common\model;



use catetree\Catetree;

class ThemeCategory extends Common
{
    protected $hidden = [
        'create_time','update_time'
    ];

    protected function getMainImgUrlAttr($val,$data){
        return self::handleImgUrl($val);
    }
    protected function getMobileImgsUrlAttr($val,$data){
        return self::handleImgUrl($val);
    }

    private function handleImgUrl($val){
        $val = str_replace('\\','/',$val);
        $arr = explode(';',$val);
        foreach ($arr as &$item){
            $item = config('APISetting.img_prefix').$item;
        }
        return $arr;
    }

    protected function productCate(){
        return $this->belongsToMany('product','product_cate','product_id','cate_id');
    }

    public function theme() {
        return $this->hasMany('theme','category_id','id');
    }


    public function getAllCateData(){
        $cateData = self::alias('a1')
            ->field('a1.*,a2.name as pname')
            ->where(['a1.status'=>1])
            ->order([
                'a1.listorder'=>'desc',
                'a1.id' => 'desc'
            ])
            ->join('theme_category a2','a1.pid=a2.id','left')
            ->select();
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
            $parentDept = $model->where([["id",'not in',$ids],['status','=',1]])->select();
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


    /**
     * api
     */
    /**
     *  获取顶级分类
     */
    public function getTopCate(){
        $data = [
            'status'    =>  1,
            'pid' =>  0,
        ];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc'
        ];
        $column =self::where($data)->order($order)->field('id,name,main_img_url,mobile_imgs_url,introduce')->select();
        return $column;
    }

    /**
     * 获取下级分类
     * @url
     * @http
     * @param $cateId
     * @return array
     */
    public static function getSonData($cateId){
        $cateTree = new Catetree();
        $ids = $cateTree->sonids($cateId, new self(),['status'=>1]);
        $data = [];
        if(count($ids) > 0){
            $data = self::_getSelCate($ids);
        }
        return $data;
    }

    /*
     * 获取分类信息  --- 多个分类
     */
    private static function _getSelCate($ids=[],$fieldStr=''){
        $field = 'id,pid,name,main_img_url,mobile_imgs_url,introduce';
        if($fieldStr) $field .= $fieldStr;
        $data = self::where('status','=',1)
            ->field($field)
            ->order([
                'listorder' => 'desc',
                'id' => 'desc'
            ])->select($ids);
        return $data;
    }

    public static function getProducts($cate_id) {
        $data = self::where([
                ['status', '=', 1],
                ['id', '=', $cate_id]
            ])
            ->hidden([
                'status','pid','listorder','create_time','update_time',
                'theme.content','theme.web_keys','theme.web_desc','theme.status',
                'theme.label_attr','theme.attributes'
            ])
            ->with(['theme' => ['product' => function($query) {
                $query->field('id,name,price,mobile_imgs_url,main_img_url,introduce');
            }]])->find();
        return $data;
    }


    public static function getCateJson($field = '', $times, $pid = 0, $resc_id)
    {
        $data = self::_cateData($field, $times, $pid, $resc_id);
        return $data;
    }


    private static function _cateData($fieldStr = '', $times, $pid = 0, $resc_id=0)
    {
        $cateTree = new Catetree();
        $field = "id,pid,name";
        $field .= ',' . $fieldStr;
        $where = [];
        if($resc_id){
            $where[] = ['','exp',Db::raw("FIND_IN_SET(1, attributes)")];
        }
        $arr = self::field($field)
            ->order(['listorder' => 'desc', 'id' => 'desc'])
            ->where($where)
            ->select();
        // 生成无限极分类树
        return $cateTree->hTree($arr, $pid, $times);
    }

}
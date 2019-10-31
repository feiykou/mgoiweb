<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13 0013
 * Time: 下午 12:22
 */

namespace app\common\model;


use catetree\Catetree;

class Gift extends Common
{
    protected $field = true;

    protected $hidden = [
        'create_time','update_time','price','listorder'
    ];


    public function product(){
        return $this->belongsToMany('product','gift_product','product_id','gift_id');
    }

    protected function getHeadImgUrlAttr($val,$data){
        return $this->handleImgUrl($val);
    }
    protected function getMainImgUrlAttr($val,$data){
        return $this->handleImgUrl($val);
    }
    protected function getMobileImgsUrlAttr($val,$data){
        return $this->handleImgUrl($val);
    }
    protected function getImgUrlAttr($val,$data){
        return $this->handleImgUrl($val);
    }
    protected function getContentAttr($val){
        $val = str_replace('src="','src='.config('APISetting.img_prefix'),$val);
        return str_replace('" title=',' title=',$val);
    }

    private function handleImgUrl($val){
        $val = str_replace('\\','/',$val);
        $arr = explode(';',$val);
        foreach ($arr as &$item){
            $item = config('APISetting.img_prefix').$item;
        }
        return $arr;
    }

    public function getAllTheme(){
        $data = [
            'status' => 1
        ];
        $order = [
            'listorder' => 'desc',
            'id'   => 'desc'
        ];
        $result = $this->where($data)->order($order)->select();
        return $result;
    }

    protected static function init(){

        Gift::afterInsert(function ($theme) {
            // 接收表单数据
            $themeData = input('post.');
            if(isset($themeData['product_ids'])){
                $theme->product()->saveAll($themeData['product_ids']);
            }
        });

        Gift::afterUpdate(function ($theme) {
            // 接收表单数据
            $themeData = input('post.');
            model('gift_product')->where('gift_id',$theme->id)->delete();
            if(isset($themeData['product_ids'])){
                $theme->product()->saveAll($themeData['product_ids']);
            }
        });

        Gift::beforeDelete(function($theme){
            $themeId = $theme->id;

            model('gift_product')->delete($themeId);

            // 删除内存中的主图
            $main_img_url = $theme->main_img_url;
            if($main_img_url){
                if(is_array($main_img_url)){
                    foreach ($main_img_url as $val){
                        $delsrc = '/upload/images/' . $val;
                        if(file_exists($delsrc)){
                            @unlink($delsrc);
                        }
                    }
                }

            }
            // 删除内存中的主图
            $head_img_url = $theme->head_img_url;
            if($head_img_url){
                if(is_array($main_img_url)){
                    foreach ($main_img_url as $val){
                        $delurl = '/upload/images/' . $val;
                        if(file_exists($delurl)){
                            @unlink($delurl);
                        }
                    }
                }
            }

        });
    }


    // api =======================
    /**
     * 获取主题详情
     *
     * 2019-10-10更改：添加$on_sale参数
     */
    public static function getThemeDetail($id, $on_sale = -1){
        $data = [
            'id' => $id
        ];
        if($on_sale !== -1){
            $data = array_merge($data,['show' => $on_sale]);
        }
        $products = self::where($data)
            ->hidden(['product.pivot'])
            ->with(['product'=>function($query){
                $query->field('id,name,main_img_url,price');
            }])
            ->find();
        return $products;
    }

    /**
     * 获取推荐的主题
     */
    public static function getRescTheme($cate_id=0,$rescId=1, $show=0){
        $data = [
            'status' => 1,
            'show' => $show,
            'pid' => $cate_id
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = self::where($data)
            ->where('','exp',"find_in_set($rescId,attributes)")
            ->field('id,name,description,main_img_url,mobile_imgs_url')
            ->order($order)
            ->select();
        return $result;
    }


    /**
     *  获取分类
     */
    public static function getCateJson($field='',$times,$pid=0){
        $data = self::_cateData($field, $times,$pid);
        return $data;
    }


    private static function _cateData($fieldStr='',$times, $pid=0){
        $cateTree = new Catetree();
        $data = [
            'show' => 1,
            'status' => 1
        ];
        $field = "id,pid,name";
        $field .= ',' . $fieldStr;
        $arr = self::field($field)
            ->where($data)
            ->order(['listorder'=> 'desc','id' => 'desc'])
            ->select();
        // 生成无限极分类树
        return $cateTree->hTree($arr, $pid, $times);
    }

    /**
     * 获取季节推荐
     */
    public static function getSeasonResc($cate_id=0) {
        $data = [
            'status' => 1,
            'show' => 0,
            'pid' => $cate_id
        ];
        $result = self::where($data)
            ->where('','exp',"find_in_set(2,attributes)")
            ->field('id,name')
            ->hidden(['product.pivot'])
            ->with(['product'=>function($query){
                $query->field('id,name,main_img_url,price');
            }])
            ->find();
        return $result;
    }

    /**
     * 获取轮播图
     * @url
     */
    public static function getGiftBanner($cate_id=0) {
        $data = [
            'status' => 1,
            'show' => 0,
            'pid' => $cate_id
        ];
        $result = self::where($data)
            ->where('','exp',"find_in_set(1,attributes)")
            ->field('id,name,main_img_url,mobile_imgs_url')
            ->find();
        return $result;
    }

    /**
     * 获取当前分类产品
     */
    public static function getProductByCate($cate_id=0) {
        $data = [
            'status' => 1,
            'id' => $cate_id
        ];
        $result = self::where($data)
            ->field('id,name,content')
            ->hidden(['product.pivot'])
            ->with(['product'=>function($query){
                $query->order('listorder desc')->field('id,name,main_img_url,price');
            }])
            ->find();
        return $result;
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
        $ids = $cateTree->sonids($cateId, new self(),['status'=>1,'show'=>1]);
        $data = [];
        if(count($ids) > 0){
            $data = self::_getSelCate($ids);
        }
        return $data;
    }

    /**
     *  获取顶级分类
     */
    public function getTopCate(){
        $data = [
            'status'    =>  1,
            'pid' =>  0,
            'show' => 1
        ];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc'
        ];
        $column =self::where($data)
            ->order($order)
            ->field('id,name,img_url')
            ->select();
        return $column;
    }

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
                'id' => 'desc',
                'show' => 1
            ])->select($ids);
        return $data;
    }

//    /**
////     *  获取面包屑链接
////     */
////    public static function getCrumb($cateId){
////        $cateTree = new Catetree();
////        $parentArr = $cateTree->parentids($cateId,new self());
////        return $parentArr;
////    }

}
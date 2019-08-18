<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/9/13 0013
 * Time: 下午 12:22
 */

namespace app\common\model;


use catetree\Catetree;

class Theme extends Common
{
    protected $field = true;

    protected $hidden = [
        'create_time','update_time','price','category_id','on_sale','listorder'
    ];

    public function product(){
        return $this->belongsTo('product','id','theme_id')->field('id,theme_id,name,main_img_url,price,link_url,code_img,mobile_imgs_url,content,introduce');
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

        Theme::beforeDelete(function($theme){
            $themeId = $theme->id;

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


    // 判断是否存在同名
    public function is_unique($name="",$id=0){
        $data = [
            ['status','neq',-1],
            ['id','neq',$id],
            ['name','=',$name]
        ];
        $result = $this->where($data)->find();
        return $result;
    }


    // api =======================
    /**
     * 获取主题详情
     */
    public static function getThemeDetail($id){
        $data = [
            'id' => $id,
            'on_sale' => 1
        ];
        $products = self::where($data)
            ->with(['product'])
            ->find();
        return $products;
    }

    public static function getThemeByCate($cate_id){
        $idArr = self::getAllSonId($cate_id);
        $data = [
            ['status','=',1],
            ['category_id','in',$idArr]
        ];
        $result = self::where($data)->order('listorder desc')->select();
        return $result;
    }

    private static function getAllSonId($id){
        $cateTree = new Catetree();
        $ids = $cateTree->childrenids($id, model('theme_category'));
        $idArr = [];
        if(count($ids) > 0){
            array_push($idArr,$ids);
        }
        $idArr[] = $id;
        return $idArr;
    }


}
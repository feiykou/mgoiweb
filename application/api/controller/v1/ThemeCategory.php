<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBeIsInteger;
use app\api\validate\Cate as CateValidate;
use app\common\model\ThemeCategory as ThemeCategoryModel;

class ThemeCategory extends BaseController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('theme_category');
    }

//    // 获取顶级分类
//    public function getTopCate(){
//        $cateData = $this->model->getTopCate();
//        return $cateData;
//    }

    /**
     * 获取下一级分类
     * @url   /themeCate/sonCate/:id
     * @http
     * @param int $cateid
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function getSonCate($id=0){
        (new IDMustBeIsInteger())->goCheck();
        if($id == 0){
            $data = $this->model->getTopCate();
        }else{
            $data = ThemeCategoryModel::getSonData($id);
        }
        return json($data);
    }


    public function getProductByTheme($cate_id=0){
        (new CateValidate())->goCheck();
        $data = ThemeCategoryModel::getProducts($cate_id);
        if(!$data) $data = [];
        return json($data);
    }


}
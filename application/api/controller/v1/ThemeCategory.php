<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBeIsInteger;
use app\api\validate\Cate as CateValidate;
use app\api\validate\Category as CategoryValidate;
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
//    public function getSonCate($id=0){
//        (new IDMustBeIsInteger())->goCheck();
//        if($id == 0){
//            $data = $this->model->getTopCate();
//        }else{
//            $data = ThemeCategoryModel::getSonData($id);
//        }
//        return json($data);
//    }


    public function getCate($times=1){
        (new CategoryValidate())->goCheck();
        $pid = input('cate_id', 0, 'int');
        $proCateData = ThemeCategoryModel::getCateJson('main_img_url, mobile_imgs_url, introduce', $times, $pid,0);
        return json($proCateData);
    }

    /**
     * 获取当前分类数据
     * @param('id','分类id','require|number')
     * @param $id
     */
    public function getCurCateData($id)
    {
        $data = $this->model->getCateById($id);
        return $data;
    }

    /**
     * 获取当前分类id的所有父级id
     * @url   /cate/parents/:id
     * @http
     * @param $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function getParentCate($id) {
        (new IDMustBeIsInteger())->goCheck();
        $cateData = ThemeCategoryModel::getCrumb($id, $this->model);
        return json($cateData);
    }


}
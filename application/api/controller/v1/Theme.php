<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\common\model\Procate;
use app\common\model\Theme as ThemeModel;
use app\api\validate\Theme as ThemeValidate;
use app\api\validate\Cate as CateValidate;
use app\common\model\ThemeCategory;
use app\common\model\Product;

class Theme extends BaseController
{

    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('theme');
    }

    /**
     * 获取分类下的主题和主题对应的产品
     * @url
     * @validate('CateValidate')
     * @param $cate_id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function getThemeByCate($cate_id){
        $themeData = [];
        if(intval($cate_id) != 0){
            $themeData = ThemeModel::getThemeByCate($cate_id);
        }
        return json($themeData);
    }

    /**
     * 获取主题详情
     * @url
     * @http
     * @return array|\PDOStatement|string|\think\Model|null
     * @throws \app\lib\exception\ParameterException
     */
    public function getThemeDetail(){
        (new IDMustBePositiveInt())->goCheck();
        $id = input('id',0,'intval');
        $data = ThemeModel::getThemeDetail($id, 1);
        return $data;
    }


    /**
     * 获取主题分类下的所有产品
     * @param('id','主题id','require|number')
     * @param $id
     */
    public function getProductByThemeCate($id)
    {
        $productIds = ThemeCategory::getProductId($id);
        $productData = [];
        if(count($productIds) > 0){
            $productData = Product::where(['status'=>1])
                ->field('id,name,price,main_img_url')
                ->select($productIds);
        }
        return json($productData);
    }


}
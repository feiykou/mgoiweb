<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\CategoryResc;
use app\common\model\Category as CategoryModel;
use app\api\validate\Category as CategoryValidate;

class Category extends BaseController
{

    /**
     * 获取栏目下分类
     * @http
     * @param $times
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function getCate($times){
        (new CategoryValidate())->goCheck();
        $pid = input('cate_id', 0, 'int');
        $proCateData = CategoryModel::getCateJson('type, link_url, main_img_url, mobile_imgs_url, description', $times, $pid);
        return json($proCateData);
    }

    public function getCategoryData($cate_id, $attr_id, $type_id) {
        (new CategoryResc())->goCheck();
        $data = [];
        switch ($type_id){
            case 1:
                $data = CategoryModel::getWenData($cate_id, $type_id, $attr_id);
                break;
            case 2:
                $data = CategoryModel::getGiftData($cate_id, $type_id, $attr_id);
                break;
            case 3:
                $data = CategoryModel::getProductData($cate_id, $type_id, $attr_id);
                break;
        }
        return json($data);
    }

}
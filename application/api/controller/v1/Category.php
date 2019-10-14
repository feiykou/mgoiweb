<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\common\model\Category as CategoryModel;
use app\api\validate\Category as CategoryValidate;

class Category extends BaseController
{
    public function getCate($times){
        (new CategoryValidate())->goCheck();
        $pid = input('cate_id', 0, 'int');
        $proCateData = CategoryModel::getCateJson('type, link_url, main_img_url, mobile_imgs_url, description', $times, $pid);
        return json($proCateData);
    }
}
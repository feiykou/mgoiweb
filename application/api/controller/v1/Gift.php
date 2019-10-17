<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\common\model\Gift as GiftModel;
use app\api\validate\Category as CategoryValidate;
use app\api\validate\Gift as GiftValidate;

class Gift extends BaseController
{
    public function getCate($times){
        (new CategoryValidate())->goCheck();
        $pid = input('cate_id', 0, 'int');
        $proCateData = GiftModel::getCateJson('main_img_url, mobile_imgs_url, description', $times, $pid);
        return json($proCateData);
    }

    /**
     * 季节推荐
     * @url
     * @http
     * @param int $cate_id
     */
    public function getSeasonResc($cate_id=0) {
        (new GiftValidate())->goCheck('cate');
        $data = GiftModel::getSeasonResc($cate_id);
        return $data;
    }

    public function getGiftBanner($cate_id=0) {
        (new GiftValidate())->goCheck('cate');
        $data = GiftModel::getGiftBanner($cate_id);
        return $data;
    }

    public function getProductByGiftCate($cate_id=0) {
        (new GiftValidate())->goCheck('cate');
        $data = GiftModel::getProductByCate($cate_id);
        return $data;
    }

}
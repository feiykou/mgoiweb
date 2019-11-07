<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\MustBePositiveInt;
use app\common\model\GiftCategory;

class GiftCate extends BaseController
{
    public function getRescGiftCate($cateId){
        (new MustBePositiveInt())->goCheck('giftResc');
        $cateIdArr = GiftCategory::getAllCateById($cateId, false);
        $result = GiftCategory::getIndexCateAndProduct($cateIdArr);
        return $result;
    }
}
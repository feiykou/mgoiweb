<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-17
 * Time: 21:30
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\MustBePositiveInt;
use app\common\model\BannerItem;
use app\api\validate\Count;
use app\common\model\Column;
use app\common\model\Procate;
use app\common\model\Product;
use \app\common\model\Theme;

class Index extends BaseController
{
    /**
     * 首页轮播图
     * @url /index/banner
     * @http
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function indexBanner(){
        // 首屏轮播
        $bannerData = BannerItem::getIndexBanner();
        return $bannerData;
    }

    /**
     * 首页推荐产品
     * @url /index/rescProduct
     * @http
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function rescProduct(){
        // 首页推荐产品
        $resProData = Product::getRescPro();
        return $resProData;
    }

    /**
     * 首页新品上新
     * @url    /index/newsProduct
     * @http   GET
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function newsProduct(){
        // 首页推荐产品
        $resProData = Product::getRescPro(2);
        return $resProData;
    }


    public function themesResc($rescId=1){
        (new MustBePositiveInt())->goCheck('resc');
        $resThemeData = Theme::getRescTheme($rescId);
        return $resThemeData;
    }


    /**
     * 获取顶级类
     * @url    /index/topCate
     * @http   GET
     * @return false|\PDOStatement|string|\think\Collection
     */
    public function topCate(){
        $proCate = new Procate();
        $topCateData = $proCate->getTopCate();
        return $topCateData;
    }

//    public function getIndexColumn($count){
//        (new Count())->goCheck();
//        // 推荐专栏
//        $resColumnData = Column::getIndexResc(9,$count);
//        return $resColumnData;
//    }

}
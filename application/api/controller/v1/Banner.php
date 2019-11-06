<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\common\model\Banner as BannerModel;
use app\lib\exception\banner\BannerException;

class Banner extends BaseController
{

    /**
     * 获取轮播图
     * @param('name','轮播图位','require')
     * @param string $name
     */
    public function getBanner($name='')
    {
        $banner = BannerModel::where('name', $name)->with(['items'])->find();
        if(!$banner) {
            throw new BannerException(['msg' => 'banner数据不存在']);
        }
        return $banner;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/5/28 0028
 * Time: 上午 9:45
 */

namespace app\admin\controller;

use app\common\model\Banner as BannerModel;
use app\common\model\BannerItem as BannerItemModel;

class BannerItem extends Base
{

    public function add($id=0){
        // 检查参数是否合法
        if(intval($id) < 1){
            $this->error("id参数不合法");
        }
        $banner = BannerModel::get($id);
        if(!$banner) $this->error("banner数据不存在");
        return $this->fetch('',[
            'banner_id' => $id
        ]);
    }

    public function edit($id){
        // 检查参数是否合法
        if(intval($id) < 1){
            $this->error("id参数不合法");
        }
        $bannerItem = BannerItemModel::get($id);
        if(!$bannerItem) $this->error("banner数据不存在");
        return $this->fetch('', [
            'bannerItem' => $bannerItem
        ]);
    }

}
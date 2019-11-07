<?php


namespace app\common\model;

use think\model\concern\SoftDelete;

class BannerItem extends Common
{

    use SoftDelete;

    protected $hidden = ['delete_time', 'create_time', 'update_time', 'listorder', 'banner_id', 'key_word'];

    protected function getMainImgUrlAttr($val, $data)
    {
        return $this->handleImgUrl($val);
    }

    protected function getMobileImgsUrlAttr($val, $data)
    {
        return $this->handleImgUrl($val);
    }
}
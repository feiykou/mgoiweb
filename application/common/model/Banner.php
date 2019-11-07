<?php


namespace app\common\model;


use think\Model;
use think\model\concern\SoftDelete;

class Banner extends Model
{

    use SoftDelete;

    protected $hidden = ['delete_time', 'create_time', 'update_time'];

    public function items ()
    {
        return $this->hasMany('BannerItem', 'banner_id', 'id');
    }

}
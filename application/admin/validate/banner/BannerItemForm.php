<?php

namespace app\admin\validate\banner;


use LinCmsTp5\validate\BaseValidate;

class BannerItemForm extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'type' => 'require|number',
        'banner_id' => 'require|number',
        'id' => 'number'
    ];

    protected $message = [
        'name' => '名称不能为空',
        'type.require' => 'type必须填写',
        'type.number' => 'type必须是数字',
        'banner_id.require' => 'banner_id必须填写',
        'banner_id.number' => 'banner_id必须是数字'
    ];

    public function sceneEdit()
    {
        return $this->append('id','require');
    }

}
<?php

namespace app\admin\validate\banner;


use LinCmsTp5\validate\BaseValidate;

class BannerForm extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'title' => 'require',
        'description' => 'require',
        'id' => 'number'
    ];

    protected $message = [
        'name' => '轮播图名称不能为空',
        'title' => '轮播图标题不能为空',
        'description' => '轮播图描述不能为空'
    ];

    public function sceneEdit()
    {
        return $this->append('id','require');
    }

}
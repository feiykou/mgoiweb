<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27
 * Time: 23:56
 */

namespace app\admin\validate;


class ThemeValidate extends BaseValidate
{

    protected $rule = [
        'name' => 'require',
        'main_img_url' => 'require',
        'content' => 'require'
    ];

    protected $message = [
        'name' => '主题名称不能为空',
        'main_img_url' => '主题封面图不能为空',
        'content' => '内容不能为空'
    ];

}
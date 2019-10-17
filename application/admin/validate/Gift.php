<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27
 * Time: 23:56
 */

namespace app\admin\validate;


class Gift extends BaseValidate
{

    protected $rule = [
        'name' => 'require'
    ];

    protected $message = [
        'name' => '礼品名称不能为空'
    ];

}
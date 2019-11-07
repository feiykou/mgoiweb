<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class Property extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'name' => 'require',
    ];

    protected $message = [
        'id.require' => 'id必须添加',
        'name' => '属性名必须填写'
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['name'],
        'edit'         => ['id', 'name']
    ];
}
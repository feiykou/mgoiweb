<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/27
 * Time: 23:56
 */

namespace app\admin\validate;


class ShopTypeAttr extends BaseValidate
{

    protected $rule = [
        'name' => 'require|max:30',
        'type_id' => 'require',
        'values' => 'require'
    ];

    protected $message = [
        'name.require' => '名称必须填写',
        'name.max' => '名称不能超过30个字',
        'type_id' => '所属分类必须',
        'values' => '属性值必须'
    ];

    protected $scene = [
        'type' => ['name'],
        'property' => ['name','type_id']
    ];
}
<?php


namespace app\api\validate;


class Product extends BaseValidate
{
    protected $rule = [
        'cateid' => 'require|isInteger',
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger',
        'sort' => 'isPositiveInteger',
        'label' => 'isPositiveInteger'
    ];

    protected $message = [
        'cateid.require' => '分类必须填写'
    ];

    protected $scene = [
        'all' => ['cateId','page','size','sort'],
        'label' => ['label']
    ];
}

<?php


namespace app\api\validate;


class Column extends BaseValidate
{
    protected $rule = [
        'cateid' => 'require|isInteger',
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'cateid.require' => '分类必须填写',
    ];

    protected $scene = [
        'all' => ['cateId','page','size']
    ];
}

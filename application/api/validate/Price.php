<?php


namespace app\api\validate;


class Price extends BaseValidate
{
    protected $rule = [
        'from' => 'require|isInteger',
        'to' => 'require|isInteger',
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'from.require' => 'from必须填写',
        'to.require' => 'to必须填写'
    ];
}
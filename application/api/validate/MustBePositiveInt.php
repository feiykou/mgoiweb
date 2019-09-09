<?php


namespace app\api\validate;


class MustBePositiveInt extends BaseValidate
{
    protected $rule = [
        'cateId' => 'require|isPositiveInteger',
        'rescId' => 'require|isPositiveInteger',
        'count' => 'isPositiveInteger',
        'id' => 'isInteger'
    ];

    protected $scene = [
        'productResc' => ['cateId','rescId'],
        'columnResc' => ['cateId','rescId','count'],
        'giftResc' => ['cateId'],
        'resc' => ['rescId'],
        'rescCount' => ['rescId','count','id']
    ];
}

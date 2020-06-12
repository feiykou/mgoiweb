<?php


namespace app\api\validate;


class TopAndBottom extends BaseValidate
{
    protected $rule = [
        'cateId' => 'isInteger',
        'id' => 'require|isPositiveInteger'
    ];


}
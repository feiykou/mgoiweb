<?php


namespace app\api\validate;


class IDMustBeIsInteger extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isInteger',
    ];
}

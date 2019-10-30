<?php


namespace app\api\validate;


class CategoryResc extends BaseValidate
{
    protected $rule = [
        'cate_id' => 'require|isPositiveInteger',
        'type_id' => 'require|isPositiveInteger',
        'attr_id' => 'require|isPositiveInteger'
    ];
}
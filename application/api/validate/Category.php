<?php


namespace app\api\validate;


class Category extends BaseValidate
{
    protected $rule = [
        'times' => 'isInteger',
        'cate_id' => 'isInteger',
    ];


}

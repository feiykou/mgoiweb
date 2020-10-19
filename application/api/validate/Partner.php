<?php


namespace app\api\validate;


class Partner extends BaseValidate
{
    protected $rule = [
        'email' => 'require|email',
    ];
}

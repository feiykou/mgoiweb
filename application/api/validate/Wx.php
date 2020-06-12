<?php


namespace app\api\validate;


class Wx extends BaseValidate
{
    protected $rule = [
        'url' => 'require|url',
    ];

    protected $scene = [
        'url' => ['url']
    ];
}
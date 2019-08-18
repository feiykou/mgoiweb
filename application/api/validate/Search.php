<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-14
 * Time: 16:19
 */

namespace app\api\validate;


class Search extends BaseValidate
{
    protected $rule = [
        'q' => 'require',
        'page' => 'isPositiveInteger',
        'size' => 'isPositiveInteger'
    ];

    protected $message = [
        'q' => '搜索内容必须添加'
    ];
}
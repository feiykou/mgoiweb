<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\api\validate;


class Cate extends BaseValidate
{
    protected $rule = [
        'cate_id' => 'require|isInteger'
    ];

    protected $message = [
        'cate_id.require' => 'cate_id必须填写'
    ];


}
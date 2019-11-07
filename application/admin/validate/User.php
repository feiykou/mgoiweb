<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class User extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'real_name' => 'require',
        'pwd' => 'require',
        'id' => 'isInteger',
        'status' => 'number|in:-1,0,1'
    ];

    protected $message = [
        'name' => '登录名必须填写',
        'real_name' => '姓名必须填写',
        'pwd' => '密码必须填写',
        'status.number' => '状态必须是数字',
        'status.in' => '状态范围不合法'
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['id', 'name','real_name','pwd'],
        'status'      => ['id','status']
    ];
}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class Category extends BaseValidate
{
    protected $regex = [ 'template_id' => '^\d'];
    protected $rule = [
        'name' => 'require|max:1000',
        'status' => 'number|in:-1,0,1',
        'listorder' => 'isInteger',
    ];

    protected $message = [
        'name.require' => '类名必须填写',
        'name.max' => '类名不能超过10个字符',
        'id' => 'id必须是数字',
        'status.number' => '状态必须是数字',
        'status.in' => '状态范围不合法',
        'listorder' => '排序必须是数字',
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['id', 'name'],
        'listorder'   => ['id','listorder'],
        'status'      => ['id','status']
    ];
}
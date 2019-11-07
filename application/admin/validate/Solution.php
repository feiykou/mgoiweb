<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class Solution extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'head_img' => 'require',
        'main_img_url' => 'require',
        'imgs_url' => 'require',
        'introduce' => 'require',
        'solu_cate_id' => 'isInteger',
        'id' => 'isInteger',
        'status' => 'number|in:-1,0,1'
    ];

    protected $message = [
        'name' => '产品名必须填写',
        'head_img' => '头图必须添加',
        'main_img_url' => '主图必须添加',
        'imgs_url' => '产品详情图必须添加',
        'introduce' => '产品介绍必须添加',
        'solu_cate_id' => '分类id必须是整数',
        'id' => 'id必须是整数',
        'status.number' => '状态必须是数字',
        'status.in' => '状态范围不合法',
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['id','name','head_img','introduce','solu_cate_id'],
        'status'      => ['id','status']
    ];
}
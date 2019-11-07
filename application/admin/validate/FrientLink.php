<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class FrientLink extends BaseValidate
{
    protected $rule = [
        'name' => 'require',
        'id' => 'isInteger',
        'link_url' => 'require|url',
        'listorder' => 'isInteger'
    ];

    protected $message = [
        'name' => '类名必须填写',
        'id' => 'id必须是数字',
        'link_url.' => '链接必须填写',
        'link_url.url' => '链接必须是有效的url地址',
        'listorder' => '排序必须是数字'
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['id', 'name','link_url'],
        'listorder'   => ['id','listorder'],
    ];
}
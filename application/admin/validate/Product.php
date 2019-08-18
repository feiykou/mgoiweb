<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 16:05
 */

namespace app\admin\validate;


class Product extends BaseValidate
{
    protected $rule = [
        'id' => 'require|isPositiveInteger',
        'name' => 'require',
        'main_img_url' => 'require',
        'imgs_url' => 'require',
        'mobile_imgs_url' => 'require',
        'code_img' => 'require',
        'price' => 'require|float',
        'market_price' => 'require|float',
        'link_url' => 'require|url',
        'cate_id' => 'require|isPositiveInteger',
        'status' => 'number|in:-1,0,1'
    ];

    protected $message = [
        'id.require' => 'id必须添加',
        'id.isPositiveInteger' => 'id必须是正整数',
        'name' => 'name必须填写',
        'main_img_url' => '缩略图必须添加',
        'imgs_url' => '产品图必须添加',
        'mobile_imgs_url' => '移动端产品图必须添加',
        'code_img' => '二维码必须添加',
        'price.require' => '价格必须添加',
        'price.float' => '价格是浮点数',
        'market_price.require' => '市场价必须添加',
        'market_price.float' => '市场价是浮点数',
        'link_url.require' => '链接必须添加',
        'link_url.url' => 'link_url是链接',
        'cate_id.require' => '分类必须添加',
        'status.number' => '状态必须是数字',
        'status.in' => '状态范围不合法'
    ];

    /** 场景 **/
    protected $scene = [
        'add'         => ['name','main_img_url','cate_id','imgs_url','mobile_imgs_url','code_img','price','market_price','link_url'],
        'status'      => ['id','status']
    ];
}
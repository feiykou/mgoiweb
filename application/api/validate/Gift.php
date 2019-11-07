<?php


namespace app\api\validate;


class Gift extends BaseValidate
{
    protected $rule = [
        'cate_id' => 'isInteger',
        'attr_id' => 'isInteger'
    ];
//
//    protected $message = [
//        'cateid.require' => '分类必须填写',
//    ];

    protected $scene = [
        'cate' => ['cate_id'],
        'cate_attr' => ['cate_id', 'attr_id']
    ];
}
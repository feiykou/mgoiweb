<?php


namespace app\admin\validate;

use LinCmsTp5\validate\BaseValidate;

class Listorder extends BaseValidate
{
    protected $rule = [
        'listorder' => 'require|checkOrder'
    ];

    protected $message = [
        'listorder.require' => '排序必须添加'
    ];

    protected function checkOrder($value)
    {
        foreach ($value as $k => $v) {
            if(!is_numeric($k)){
                return 'id必须是数字';
            }
            if(!is_numeric($v)){
                return '排序值必须是数字';
            }
        }
        return true;
    }
}
<?php


namespace app\api\validate;


class Theme extends BaseValidate
{

    protected $rule = [
        'ids' => 'checkIds'
    ];

    protected $singleRule = [
        'id' => 'isPositiveInteger'
    ];

    protected function checkIds($ids) {
        if(empty($ids)){
            throw new ParameterException([
                'msg' => '主题id不能为空'
            ]);
        }
        $idArr = explode(',',$ids);
        foreach ($idArr as $val){
            $this->singleId(['id'=>$val]);
        }
    }

    protected function singleId($value) {
        $validate = new BaseValidate($this->singleRule);
        $result = $validate->check($value);
        if(!$result){
            throw new ParameterException([
                'msg' => '获取产品参数错误',
            ]);
        }
    }

    protected $scene = [
        'cates' => ['ids']
    ];
}
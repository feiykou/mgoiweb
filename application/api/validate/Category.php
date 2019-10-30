<?php


namespace app\api\validate;


class Category extends BaseValidate
{
    protected $rule = [
        'times' => 'isInteger',
        'cate_id' => 'isInteger',
        'attr_id' => 'require|isPositiveInteger',
        'type_id' => 'require|isPositiveInteger'
    ];

    public function sceneResc()
    {
        return $this->only(['attr_id','type_id','cate_id'])
            ->append('cate_id', 'require');
    }

    protected $scene = [
        'cate' => ['times', 'cate_id']
    ];
}

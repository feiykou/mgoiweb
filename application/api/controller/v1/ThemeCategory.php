<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;

class ThemeCategory extends BaseController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('theme_category');
    }

    // 获取顶级分类
    public function getTopCate(){
        $cateData = $this->model->getTopCate();
        return $cateData;
    }


}
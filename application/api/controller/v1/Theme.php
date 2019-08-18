<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\common\model\Theme as ThemeModel;
use app\api\validate\Cate as CateValidate;

class Theme extends BaseController
{

    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('theme');
    }

    public function getThemeByCate($cate_id){
        (new CateValidate())->goCheck();
        if($cate_id == 0){
            $themeData = $this->model->getAllTheme();
        }else{
            $themeData = ThemeModel::getThemeByCate($cate_id);
        }
        return $themeData;
    }

    public function getThemeDetail(){
        (new IDMustBePositiveInt())->goCheck();
        $id = input('id',0,'intval');
        $data = ThemeModel::getThemeDetail($id);
        return $data;
    }
}
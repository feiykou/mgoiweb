<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\Cate as CateValidate;
use app\api\validate\IDMustBePositiveInt;
use app\common\model\Album as AlbumModel;

class Album extends BaseController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('album');
    }

    public function getThemeByCate($cate_id){
        (new CateValidate())->goCheck();
        if($cate_id == 0){
            $themeData = $this->model->getAllTheme();
        }else{
            $themeData = AlbumModel::getThemeByCate($cate_id);
        }
        return $themeData;
    }

    public function getThemeDetail(){
        (new IDMustBePositiveInt())->goCheck();
        $id = input('id',0,'intval');
        $data = AlbumModel::getThemeDetail($id, 1);
        return $data;
    }
}
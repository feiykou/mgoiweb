<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-04
 * Time: 23:39
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBeIsInteger;
use app\api\validate\IDMustBePositiveInt;
use app\common\model\Procate;

class Cate extends BaseController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('procate');
    }

    public function getAllCate(){
        $proCateData = $this->model->getCateJson();
        return $proCateData;
    }

    public function getTopCate(){
        $cateData = $this->model->getTopCate();
        return $cateData;
    }

    public function getSonById(){
        $id = input('cate_id',0,'intval');
        $cateData = $this->model->getAllSonData($id);
        return json($cateData);
    }

    /**
     * 获取下一级分类
     * @url   /cate/sonCate/:id
     * @http
     * @param int $cateid
     * @return array|false|\PDOStatement|string|\think\Collection
     */
    public function getSonCate($id=0){
        (new IDMustBeIsInteger())->goCheck();
        if($id == 0){
            $data = $this->model->getTopCate();
        }else{
            $data = Procate::getSonData($id);
        }
        return json($data);
    }

    /**
     * 获取当前分类id的所有父级id
     * @url   /cate/parents/:id
     * @http
     * @param $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function getParentCate($id){
        (new IDMustBePositiveInt())->goCheck();
        $cateData = Procate::getCrumb($id);
        return json($cateData);
    }

}
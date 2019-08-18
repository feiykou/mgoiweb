<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-04
 * Time: 23:39
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
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

}
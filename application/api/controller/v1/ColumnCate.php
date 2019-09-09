<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-11
 * Time: 17:20
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;

class ColumnCate extends BaseController
{
    public function __construct()
    {
        parent::__construct();
        $this->model = model('column_cate');
    }

    public function getColumnCateById($id){
        if(intval($id) <= 0) return;
        $cateData = $this->model->getCateById($id);
        return $cateData;
    }

    /**
     * 获取当前分类下的所有子分类，并设置成树形结构
     */
    public function getSonById(){
        $id = input('cate_id',0,'intval');
        $cateData = $this->model->getAllSonData($id);
        return json($cateData);
    }
}
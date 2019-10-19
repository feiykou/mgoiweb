<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-11
 * Time: 17:20
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\common\model\ColumnCate as ColumnCateModel;
use app\api\validate\Category as CategoryValidate;
use app\api\validate\Cate as CateValidate;

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

    /**
     * 获取分类
     * @url
     * @http
     * @param $times 层级
     * @param $cate_id 分类id
     * @return \think\response\Json
     */
    public function getCate($times){
        (new CategoryValidate())->goCheck();
        $pid = input('cate_id', 0, 'int');
        $proCateData = ColumnCateModel::getCateJson('img_url,create_time, description', $times, $pid);
        return json($proCateData);
    }

    public function getCrumbCate($cate_id=0){
        (new CateValidate())->goCheck();
        $result = ColumnCateModel::getCrumb($cate_id);
        return json($result);
    }
}
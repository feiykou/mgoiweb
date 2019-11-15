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

    /**
     * 获取全部的分类和分类下的文章数量
     * @url   /columnCate/all
     * @return array|\PDOStatement|string|\think\Collection
     */
    public function getAllColumnCate()
    {
        $result = ColumnCateModel::getAllCate();
        return $result;
    }


    /**
     * 获取当前分类及分类下的所有专栏
     * @url   /columnCate/column/:id
     * @param('id','专栏分类','require|number')
     * @param $id
     */
    public function getColumnAndCate($id)
    {
        $result = ColumnCateModel::getColumnAndCateByCate($id);
        return $result;
    }



    /**
     * 获取当前专栏分类数据
     * @url
     * @http
     * @param $id
     */
    public function getColumnCateById($id) {
        if(intval($id) <= 0) return;
        $cateData = $this->model->getCateById($id);
        return $cateData;
    }

    /**
     * 获取推荐分类
     * @url columnCate/resc?resc_id=1&simple=0|1
     * simple: 1 代表分类下无专栏   0 代表分类下有专栏
     * attr_id（推荐id）：1：首页推荐  2：栏目推荐
     * @param('resc_id','推荐','number')
     * @param('simple','是否存在专栏','number')
     */
    public function getIndexRescCateAndColumn($resc_id=1, $simple=1)
    {
        $result = ColumnCateModel::getRescCateAndColumn($resc_id, $simple);
        return $result;
    }

    /**
     * 获取当前分类下的所有子分类，并设置成树形结构
     */
//    public function getSonById(){
//        $id = input('cate_id',0,'intval');
//        $cateData = $this->model->getAllSonData($id);
//        return json($cateData);
//    }

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

    /**
     * 获取面包屑数据
     * @param int $cate_id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function getCrumbCate($cate_id=0){
        (new CateValidate())->goCheck();
        $result = ColumnCateModel::getCrumb($cate_id);
        return json($result);
    }


}
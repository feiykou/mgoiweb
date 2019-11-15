<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-04
 * Time: 21:55
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\MustBePositiveInt;
use app\api\validate\Search;
use app\common\model\Column as ColumnModel;
use app\common\model\ColumnCate;
use app\api\validate\Column as ColumnValidate;
use app\api\validate\Cate as CateValidate;
use catetree\Catetree;

class Column extends BaseController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('column');
    }

    /**
     * 获取专栏详情
     * @url   column/detail/:id
     * @param $id  专栏id
     */
    public function detail($id){
        if(intval($id) <= 0) return;
        $detailData = $this->model->getNewsData($id);
        return $detailData;
    }

    /**
     * 获取最新的专栏文章
     * @url  /column/news?count=6
     * @param('count','专栏数量','number')
     * @param int $count 专栏数量
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getNewsColumn($count=6)
    {
        $result = ColumnModel::where([
            'status' => 1
        ])->limit($count)
          ->field('id,name,introduce,main_img_url,mobile_imgs_url,cate_id,create_time')
          ->order('create_time desc')
          ->select();
        return $result;
    }


    // 暂无用处
    public function getTopCate(){
        $cate = ColumnCate::getTopCate();
        return $cate;
    }

    /**
     * 获取首页推荐
     * @url
     * @param $cateId
     * @param int $rescId
     * @param $count
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\ParameterException
     */
    public function getIndexColumn($cateId,$rescId=1,$count=4){
        (new MustBePositiveInt())->goCheck('columnResc');
        $cateIdArr = ColumnCate::getAllCateById($cateId);
        // 推荐专栏
        $resColumnData = ColumnModel::getIndexResc($cateIdArr, $rescId, $count);
        return $resColumnData;
    }

    /**
     * 获取推荐的专栏
     * @url
     * @http  column/resc
     * @param int $rescId
     * @param int $count
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\ParameterException
     */
    public function getRescColumn($rescId=1,$count=1,$id=0){
        (new MustBePositiveInt())->goCheck('rescCount');
        // 推荐专栏
        $resColumnData = ColumnModel::getResc($rescId, $count, $id);
        return $resColumnData;
    }

    /**
     * 获取分类下的所有产品
     * @url  /product/list/:cateid?page=1&size=10
     * @http
     * @param $cateid
     * @return false|\PDOStatement|string|\think\Collection
     * @throws CategoryException
     */
    public function getColumnsByCate($cateId=0,$page=1,$size=10){
        (new ColumnValidate())->goCheck('all');
        if($cateId == 0){
            $productArr = ColumnModel::where('status','=',1)
                ->order('listorder desc')
                ->field('id,name,sub_name,mobile_imgs_url,main_img_url,introduce,create_time')
                ->paginate($size,true,['page'=>$page]);
        }else{
            $catetree = new Catetree();
            $sonids = $catetree->childrenids($cateId, new ColumnCate());
            $sonids[] = intval($cateId);
            $productArr = ColumnModel::getColumnsByCate($sonids,$page,$size);
        }
        return json($productArr);
    }
//    public function getColumnByCateId($cate_id=0){
//        $data = ColumnModel::getNewsIndexData($cate_id);
//        return $data;
//    }

    /**
     * 获取上下篇
     * @url   /column/topBottom/:id
     * @http
     * @param $id
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     * @throws \think\db\exception\DataNotFoundException
     * @throws \think\db\exception\ModelNotFoundException
     * @throws \think\exception\DbException
     */
    public function getTopAndBottom($id){
        (new IDMustBePositiveInt())->goCheck();
        $data = [
            ['status','=',1]
        ];


        $all_id = $this->model->field('id')->where($data)->order('id asc')->select();
        $next_id = $pre_id = '';
        foreach ($all_id as $key => $value){
            if($value['id'] == $id){
                // 获取下一篇
                if(isset($all_id[$key+1])){
                    $next_id = $all_id[$key+1]['id'];
                }

                // 获取上一篇
                if(isset($all_id[$key-1])){
                    $pre_id = $all_id[$key-1]['id'];
                }
            }
        }

        if($next_id != ''){
            $next_info = $this->model->field('id,name')->where('id',$next_id)->find();
        } else {
            $next_info = '';
        }

        if($pre_id != ''){
            $prev_info = $this->model->field('id,name')->where('id',$pre_id)->find();
        } else {
            $prev_info = '';
        }

        return json([
            'next_info' => $next_info,
            'prev_info' => $prev_info
        ]);
    }


    /**
     * 专栏搜索
     * @url   /column/search?q=''&page=&size
     * @http
     * @param $data
     * @param int $page
     * @param int $size
     * @return array
     */
    public function search($page=1, $size=4){
        (new Search())->goCheck('');
        $data = input('get.');
        if(empty($data['q'])){
            return [
                'data' => [],
                'total' => 0
            ];
        }
        $searchData = $this->model->getSearchResult($data,$size,$page);
        if($searchData->isEmpty()){
            $searchData = [
                'data' => [],
                'total' => 0
            ];
        }
        return json($searchData);
    }

    /**
     * 获取子分类下的专栏
     */
    public function getColumnList($cate_id=0){
        (new CateValidate())->goCheck();
        $data = ColumnCate::getColumnList($cate_id);
        return $data;
    }


}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-05-04
 * Time: 23:32
 */

namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\MustBePositiveInt;
use app\api\validate\Search;
use app\common\model\Procate;
use app\common\model\Product as ProductModel;
use app\api\validate\Product as ProductValidate;
use catetree\Catetree;

class Product extends BaseController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('product');
    }

    public function getRescProductByCate(){
        (new MustBePositiveInt())->goCheck('productResc');
        $cateId = input('cateId',0,'intval');
        $rescId = input('rescId',0,'intval');
        $cateIdArr = Procate::getAllCateById($cateId);
        $data = ProductModel::getRescCateProducts($cateIdArr,$rescId);
        return json($data);
    }

    public function lst($cate_id=0){
        if($cate_id == 0){
            $products = $this->model->getAllProData();
        }else{
            $cate_ids = Procate::getAllCateById($cate_id);
            $productIdsArr = model('product_cate')
                ->where([['cate_id','in',$cate_ids]])
                ->field('product_id')
                ->select();
            $ids = [];
            foreach ($productIdsArr as $data){
                array_push($ids,$data['product_id']);
            }
            $products = $this->model->getProByIds($ids);
        }
        return $products;
    }

    /**
     *
     * @url
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
     * 获取产品详情
     * @url     /product/:id/detail
     * @http    get
     * @param   $id
     * @return  array|false|\PDOStatement|string|\think\Model
     * @throws  ProductException
     */
    public function getOne($id)
    {
        (new IDMustBePositiveInt())->goCheck();
        $product = ProductModel::getProductDetail($id);
        return $product;
    }

    /**
     * 获取分类下的所有产品
     * @url  /product/list/:cateid?page=1&size=10
     * @http
     * @param $cateid
     * @return false|\PDOStatement|string|\think\Collection
     * @throws CategoryException
     */
    public function getProductByCate($cateid=0,$page=1,$size=10){
        (new ProductValidate())->goCheck('all');
        $sort = input('sort',0,'intval');
        if($cateid == 0){
            $order = ['listorder'=>'desc'];
            if($sort){
                $type = $sort == 1 ? 'desc' : 'asc';
                array_push($order,[
                    'price' => $type
                ]);
            }
            $productArr = ProductModel::where('status','=',1)
                ->order($order)
                ->field('id,name,introduce,main_img_url,price,name_desc')
                ->paginate($size,true,['page'=>$page]);
        }else{
            $catetree = new Catetree();
            $sonids = $catetree->childrenids($cateid, new Procate());
            $sonids[] = intval($cateid);
            $productArr = ProductModel::getProductsByCate($sonids,$sort,$page,$size);
        }
        return json($productArr);
    }

    /**
     *  获取推荐产品
     * @url
     * @http  product/resc
     * @param int $rescId
     * @param int $count
     * @return array|\PDOStatement|string|\think\Collection
     * @throws \app\lib\exception\ParameterException
     */
    public function getRescProduct($rescId=1,$count=1,$id=0){
        (new MustBePositiveInt())->goCheck('rescCount');
        // 推荐专栏
        $resColumnData = ProductModel::getResc($rescId, $count, $id);
        return $resColumnData;
    }


}
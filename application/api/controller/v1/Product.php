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
use app\api\validate\Price;
use app\api\validate\Search;
use app\common\model\Procate;
use app\common\model\Product as ProductModel;
use app\api\validate\Product as ProductValidate;
use think\facade\Request;

class Product extends BaseController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('product');
    }

    /**
     * 获取产品分类下推荐的产品
     * @url  /product/rescbycate?cateId=1&rescId=6
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
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
            $products = $this->model->getAPIAllProData();
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
     * 搜索
     * @url
     * @http
     * @param $data
     * @param int $page
     * @param int $size
     * @return array
     */
    public function search($page=1, $size=4){
        (new Search())->goCheck();
        $q = urldecode(input('q'));
        if(empty($q)){
            return [
                'data' => [],
                'total' => 0
            ];
        }
        $sort = input('sort',0,'intval');
        $searchData = $this->model->getSearchResult($q,$sort,$size,$page);
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
     * @url  /product/list/:cateid?page=1&count=10
     * @param('sort','价格排序','number')
     * @param('cateid','分类id','require|number')
     * @return false|\PDOStatement|string|\think\Collection
     * @throws CategoryException
     */
    public function getProductByCate($cateid)
    {
        $cateid = input('cateid', 0, 'intval');
        $sort = input('sort', 1, 'intval');
        $products = ProductModel::getProductByCateAndPage($cateid, $sort);
        return json($products);
    }
//    public function getProductByCate($cateid=0,$page=1,$size=10){
//        (new ProductValidate())->goCheck('all');
//        $sort = input('sort',0,'intval');
//        if($cateid == 0){
//            $order = ['listorder'=>'desc'];
//            if($sort){
//                $type = $sort == 1 ? 'desc' : 'asc';
//                array_push($order,[
//                    'price' => $type
//                ]);
//            }
//            $productArr = ProductModel::where('status','=',1)
//                ->order($order)
//                ->field('id,name,introduce,main_img_url,price,name_desc')
//                ->paginate($size,true,['page'=>$page]);
//        } else {
//            $catetree = new Catetree();
//            $sonids = $catetree->childrenids($cateid, new Procate());
//            $sonids[] = intval($cateid);
//            $productArr = ProductModel::getProductsByCate($sonids,$sort,$page,$size);
//        }
//        return json($productArr);
//    }

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

    /**
     * 获取产品通过价格
     * @url
     * @http
     * @param $from 最低价格
     * @param $to   最高价格
     * @param int $page
     * @param int $size
     * @return \think\response\Json
     * @throws \app\lib\exception\ParameterException
     */
    public function getProductByPrice($from, $to, $page=1, $size=10){
        (new Price())->goCheck();
        $result = ProductModel::getProductByPrice($from, $to, $page, $size);
        return json($result);
    }

    /**
     * 获取栏目下的所有产品
     * @url  /product/list/:cateid?page=1&size=10
     * @http
     * @param $cateid 栏目id
     * @return false|\PDOStatement|string|\think\Collection
     * @throws CategoryException
     */
    public function getProductByCategory($cateid=0,$page=1,$size=10){
        (new ProductValidate())->goCheck('all');
        $sort = input('sort',0,'intval');
        $order = ['listorder'=>'desc'];
        if($sort){
            $type = $sort == 1 ? 'desc' : 'asc';
            array_push($order,[
                'price' => $type
            ]);
        }
        $productArr = ProductModel::where([
            'status' => 1,
            'column_id' => $cateid
        ])->order($order)
            ->field('id,name,introduce,main_img_url,price,name_desc')
            ->paginate($size,true,['page'=>$page]);
        return json($productArr);
    }


    public function getProductByLabel($label=1){
        (new ProductValidate())->goCheck('label');
        $result = ProductModel::getProductByLabel($label);
        return $result;
    }


}
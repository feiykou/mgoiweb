<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBeIsInteger;
use app\api\validate\IDMustBePositiveInt;
use app\common\model\Gift as GiftModel;
use app\api\validate\Category as CategoryValidate;
use app\api\validate\Gift as GiftValidate;
use app\api\validate\Product as ProductValidate;
use app\common\model\Product as ProductModel;
use catetree\Catetree;

class Gift extends BaseController
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('gift');
    }

    public function getCate($times){
        (new CategoryValidate())->goCheck();
        $pid = input('cate_id', 0, 'int');
        $proCateData = GiftModel::getCateJson('main_img_url, mobile_imgs_url, description', $times, $pid);
        return json($proCateData);
    }

    /**
     * 季节推荐
     * @url
     * @http
     * @param int $cate_id
     */
    public function getSeasonResc($cate_id=0) {
        (new GiftValidate())->goCheck('cate');
        $data = GiftModel::getSeasonResc($cate_id);
        return $data;
    }

    public function getGiftBanner($cate_id=0) {
        (new GiftValidate())->goCheck('cate');
        $data = GiftModel::getGiftBanner($cate_id);
        return $data;
    }

    public function getProductByGiftCate($cate_id=0) {
        (new GiftValidate())->goCheck('cate');
        $data = GiftModel::getProductByCate($cate_id);
        return $data;
    }

    /**
     * 获取分类下（包括所有子分类）的所有产品
     * @url  /product/list/:cateid?page=1&size=10
     * @http
     * @param $cateid
     * @return false|\PDOStatement|string|\think\Collection
     * @throws CategoryException
     */
    public function getProductByGiftSonCate($cateid=0,$page=1,$size=10){
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
            $sonids = $catetree->childrenids($cateid, new GiftModel(), [
                'show' => 1,
                'status' => 1
            ]);
            $sonids[] = intval($cateid);
            $productArr = ProductModel::getProductsByGiftCate($sonids,$sort,$page,$size);
        }
        return json($productArr);
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
        if(intval($id) == 0){
            $data = $this->model->getTopCate();
        }else{
            $data = GiftModel::getSonData($id);
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
        (new IDMustBeIsInteger())->goCheck();
        $cateData = GiftModel::getCrumb($id);
        return json($cateData);
    }



}
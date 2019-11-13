<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/8/21
 * Time: 23:59
 */

namespace app\admin\controller;

use app\admin\validate\Gift as GiftValidate;
use catetree\Catetree;

class Gift extends Base
{
    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('gift');
    }

    public function index(){
        $themeData = $this->model->getAllTheme();
        $sortArr = sortData($themeData);
        $this->assign([
            'themeData' => $sortArr
        ]);
        return view();
    }

    public function add(){
        // 商品分类
        $Category=new Catetree();
        $CategoryRes=$this->model->findAll([],'listorder DESC');
        $CategoryRes=$Category->Catetree($CategoryRes);

        // 获取全部商品
        $proAllData = model('product')->getAllProData();
        $attrData = config('attributes.gift_resc_type');
        $labelsData = config('attributes.labels_type');
        $this->assign([
            'CategoryRes'  => $CategoryRes,
            'attrData'     => $attrData,
            'labelsData'  => $labelsData,
            'proAllData'  => $proAllData
        ]);
        return view();
    }

    public function edit($id=0){
        if(intval($id) < 1){
            $this->error("参数不合法");
        }
        $theme_id = intval($id);
        // 获取当前产品基本信息
        $themeData = $this->model->getThemeDetail($theme_id);
        $themeData['theme_product'] = $this->_setThemeProduct($themeData['product']);
        // 商品分类
        $Category=new Catetree();
        $CategoryRes=$this->model->findAll([],'listorder DESC');
        $CategoryRes=$Category->Catetree($CategoryRes);

        $attrData = config('attributes.gift_resc_type');
        $labelsData = config('attributes.labels_type');

        $themeData['attributes'] = explode(',',$themeData['attributes']);
        $themeData['label_attr'] = explode(',',$themeData['label_attr']);

        // 获取全部商品
        $proAllData = model('product')->getAllProData();
        $this->assign([
            'themeData'      => $themeData,
            'CategoryRes'    => $CategoryRes,
            'attrData'     => $attrData,
            'labelsData'  => $labelsData,
            'proAllData'  => $proAllData
        ]);
        return view();
    }

    private function _setThemeProduct($data){
        $sData = [];
        if(count($data) >= 1){
            foreach ($data as $k => $v){
                array_push($sData, $v['id']);
            }
        }
        return $sData;
    }

    public function save(){
        if(!request()->post()){
            $this->error("请求失败");
        }
        $validate = (new GiftValidate())->goCheck();
        if(!$validate['type']){
            $this->result("",'0',$validate['msg']);
        }

        // 获取请求数据
        $data = input('post.');
        $is_exist_id = empty($data['id']);
//        //判断是否存在同名
//        $is_unique = $this->model->is_unique($data['name'],$is_exist_id?0:$data['id']);
//
//        if($is_unique){
//            $this->result('','0','存在同名类');
//        }

        $data['attributes'] = keyInArray($data,'attributes')? implode(',',$data['attributes']):'';
        $data['label_attr']  = keyInArray($data,'label_attr')? implode(',',$data['label_attr']):'';

        // 更新数据
        if(!$is_exist_id){
            return $update = $this->update($data);
        }
        // 添加数据
        $result = $this->model->save($data);
        if($result){
            $this->result(url('index'),'1','添加成功');
        }else{
            $this->result('','0','添加失败');
        }
    }

    public function update($data){
        $result = $this->model->save($data,['id' => intval($data['id'])]);
        if($result){
            $this->result(url('index'),'1','更新成功');
        }else{
            $this->result(url('index'),'0','无更新');
        }
    }

    public function del($id){
        $result = $this->model->destroy($id);
        // 返回状态码
        if($result){
            $this->result($_SERVER['HTTP_REFERER'], 1, '删除完成');
        }else{
            $this->result($_SERVER['HTTP_REFERER'], 0, '删除失败');
        }
    }

    public function edituploadImg(){
        if($_FILES['file']['tmp_name']){
            $file = request()->file('file');
            $info = $file->move('upload');
            if($info){
                $img_url = DIRECTORY_SEPARATOR . 'upload' . DIRECTORY_SEPARATOR . $info->getSaveName();
            }
        }
        if(!empty($img_url)){
            $json = [
                "code"=> 0, //0表示成功，其它失败
                "msg" => "上传成功", //提示信息 //一般上传失败后返回
                "data" =>[
                   "src"=>$img_url,
                    "title" => '图片'
                ]
            ];
            return $json;
        }else{
            $json = [
                "code"=> 1, //0表示成功，其它失败
                "msg" => "上传失败", //提示信息 //一般上传失败后返回
                "data" =>[
                    "src"=> ''
                ]
            ];
            return $json;
        }
    }


    /* 删除图片 */
    public function ajaxDelPic($id){
        $productImage = db('product_image');
        $product_img = $productImage->find($id);
        @unlink($product_img.img_url);
        $del = $productImage->delete($id);
    }

    // 排序
    public function listorder($id,$listorder){
        $result = $this->model->save(['listorder'=>$listorder],['id'=>$id]);
        if($result){
            $this->result($_SERVER['HTTP_REFERER'], 1, '更新完成');
        }else{
            $this->result($_SERVER['HTTP_REFERER'], 0, '更新失败');
        }
    }
}
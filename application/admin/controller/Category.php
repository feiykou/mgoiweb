<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 15:24
 */

namespace app\admin\controller;


use app\admin\validate\Category as CateValidate;
use app\common\model\Category as CategoryModel;
use catetree\Catetree;

class Category extends Base
{

    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model("Category");
    }


    // 获取分类列表和子类列表
    public function index(){
        $categorys = $this->model->getIndexCateData();
        $page = $categorys->render();
        $sortArr = sortData($categorys);
        return $this->fetch('', [
            'categorys' => $sortArr,
            'page' => $page
        ]);
    }

    // 请求add页面
    public function add(){
        $categorys = $this->model->getAllCate();
        $sortArr = sortData($categorys);
        $category_type = config('attributes.category_type');
        return $this->fetch('',[
            "categorys"  =>  $sortArr,
            "category_type" => $category_type
        ]);
    }

    // 添加和更新数据
    public function save(){
        if(!request()->isPost()){
            $this->error("请求失败");
        }
        $validate = (new CateValidate())->goCheck('add');
        if(!$validate['type']){
            $this->result("",'0',$validate['msg']);
        }
        // 获取请求数据
        $data = input('post.');
        $is_exist_id = empty($data['id']);
        //判断是否存在同名
        $is_unique = $this->model->is_unique($data['name'],$is_exist_id?0:$data['id']);
        if($is_unique){
            $this->result('','0','存在同名类');
        }

        // 更新数据
        if(!$is_exist_id){
            return $this->update($data);
        }
        // 添加数据
        $result = $this->model->save($data);
        if($result){
            $this->result(url('index'),'1','添加成功');
        }else{
            $this->result("",'0','添加失败');
        }

    }

    // 编辑
    public function edit($id=0){
        if(intval($id) < 1){
            $this->error("参数不合法");
        }
        $categorys = $this->model->getAllCate();
        $sortArr = sortData($categorys);
        $category = $this->model->get($id);
        $category_type = config('attributes.category_type');
        return $this->fetch('',[
            'categorys'     => $sortArr,
            'category'      => $category,
            "category_type" => $category_type
        ]);
    }

    // 更新
    public function update($data){
        $result = $this->model->save($data,['id' => intval($data['id'])]);
        if($result){
            $this->result(url('index'),'1','添加成功');
        }else{
            $this->result("",'0','添加失败');
        }
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

    //删除
    public function del($id){
        if(intval($id) < 0 ){
            $this->error("参数不合法");
        }
        $catetree = new Catetree();
        $sonids = $catetree->childrenids($id, $this->model);
        $sonids[] = intval($id);
        $result = CategoryModel::destroy($sonids);

        // 返回状态码
        if($result){
            $this->result($_SERVER['HTTP_REFERER'], 1, '删除完成');
        }else{
            $this->result($_SERVER['HTTP_REFERER'], 0, '删除失败');
        }
    }

    /**
     * 删除多条
     */
    function delSel(){
        $idArr = input('post.')['idsArr'];
        if(!is_array($idArr)){
            $this->error('参数错误');
        }
        $catetree = new Catetree();
        $allson = $catetree->pdel($idArr, $this->model);
        $result = CategoryModel::destroy($allson);

        if($result){
            $this->result($_SERVER['HTTP_REFERER'], 1, '删除完成');
        }else{
            $this->result($_SERVER['HTTP_REFERER'], 0, '删除失败');
        }
    }

//    public function crumb($cur_pid=0){
//        $str = [];
//        $result = $this->model->where([
//            'id' => $cur_pid,
//            'status'    => ['neq',-1]
//        ])->find();
//        if($result){
//            $str['name'] = $result['name'];
//        }
//        return $str;
//    }

}
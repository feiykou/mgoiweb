<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\validate\IDMustBePositiveInt;
use app\api\validate\Paginate;

class News extends BaseController
{

    private $model;
    public function __construct()
    {
        parent::__construct();
        $this->model = model('news');
    }

    public function getAllNews($page=1,$size=10){
        (new Paginate())->goCheck();
        $result = $this->model->getAllNews($page, $size);
        return json($result);
    }

    public function detail($id){
        (new IDMustBePositiveInt())->goCheck();
        $detailData = $this->model->getNewsData($id);
        return $detailData;
    }

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

}
<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/8 0008
 * Time: 下午 20:22
 */

namespace app\common\model;


use catetree\Catetree;
use think\Model;

class Column extends Model
{

    protected $hidden = [
        'delete_time','update_time','status','column_id','listorder','click_num'
    ];

    public function cate(){
        return $this->hasMany('column_cate','id','cate_id');
    }

    protected function getMainImgUrlAttr($val,$data){
        return $this->handleImgUrl($val);
    }
    protected function getMobileImgsUrlAttr($val,$data){
        return $this->handleImgUrl($val);
    }

    protected function getContentAttr($val){
        $val = str_replace('src="','src="'.config('APISetting.img_prefix'),$val);
        return $val;
    }

    private function handleImgUrl($val){
        $val = str_replace('\\','/',$val);
        $arr = explode(';',$val);
        foreach ($arr as &$item){
            $item = config('APISetting.img_prefix').$item;
        }
        return $arr;
    }

    public function getAllNewsData($data=[]){
        $data[] = ['status','neq',-1];
        $order = [
            'listorder' => 'desc',
            'id'        => 'desc',
        ];
        $result = self::where($data)
            ->order($order)
            ->with('cate')
            ->select();
        return $result;
    }

    // 判断是否存在同名
    public function is_unique($name="",$id=0){
        $data = [
            ['status','neq',-1],
            ['id','neq',$id],
            ['name','=',$name]
        ];
        $result = $this->where($data)->find();
        return $result;
    }

    // 删除元素
    public function delSelChild($idArr=[]){
        $data = [
            ['id','in',$idArr]
        ];
        $result = $this->where($data)->update(['status'=>-1]);
        db('product_prop')->where(['product_id'=>['in',$idArr]])->delete();
        return $result;
    }


    /**
     * 前端和后台公用
     */
    public function getNewsData($id=0, $mark=false){
        $data = [
            'id'     => $id
        ];
        if($mark){
           $data['status'] = 1;
        }
        $proData = self::where($data)->find();
        return $proData;
    }



    /**
     * 前台数据调用
     */

    // 获取推荐专栏
    public static function getResc($rescId=1,$count=1,$id=0){
        $data = [
            ['status','=',1]
        ];
        if($id){
            array_push($data,['id','neq',$id]);
        }
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = self::where($data)
            ->order($order)
            ->where('','exp',"find_in_set($rescId,attributes)")
            ->limit($count)
            ->field('id,name,mobile_imgs_url,main_img_url,introduce,create_time')
            ->select();
        return $result;
    }

    public static function getIndexResc($cateIds,$rescId=1,$count=4){
        $data = [
            ['status','=',1],
            ['cate_id','in',$cateIds]
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = self::where($data)
            ->order($order)
            ->where('','exp',"find_in_set($rescId,attributes)")
            ->limit($count)
            ->field('id,name,mobile_imgs_url,main_img_url,introduce,create_time')
            ->select();
        return $result;
    }

    // 通过分类获取产品
    public static function getColumnsByCate($cateIds,$page=1,$size=10){
        $data = [
            ['status','=',1],
            ['cate_id','in',$cateIds]
        ];
        $order = [
            'listorder' => "desc",
            'id'        => "desc"
        ];
        $result = self::where($data)
            ->order($order)
            ->field('id,name,sub_name,mobile_imgs_url,main_img_url,introduce,create_time')
            ->paginate($size,true,['page'=>$page]);
        return $result;
    }

    /**
     * 获取搜索结果
     */
    public function getSearchResult($params,$size=10,$page=1){
        $data = [
            ['status','=',1],
            ['name','like','%'.$params['q'].'%']
        ];
        $order = [
            'listorder' => 'desc',
            'create_time' => 'desc'
        ];

        $data = self::where($data)
            ->order($order)
            ->field('id,name,sub_name,mobile_imgs_url,main_img_url,introduce,create_time')
            ->paginate($size,false,['page'=>$page]);
        return $data;
    }

    public static function getCateJson($field='',$times,$pid=0){
        $data = self::_cateData($field, $times,$pid);
        return $data;
    }


    private static function _cateData($fieldStr='',$times, $pid=0){
        $cateTree = new Catetree();
        $field = "id,pid,name";
        $field .= ',' . $fieldStr;
        $arr = self::field($field)
            ->order(['listorder'=> 'desc','id' => 'desc'])
            ->select()
            ->each(function($data){
            });
        // 生成无限极分类树
        return $cateTree->hTree($arr, $pid, $times);
    }


}
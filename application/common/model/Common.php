<?php


namespace app\common\model;


use catetree\Catetree;
use think\Db;
use think\facade\Request;
use think\Model;

class Common extends Model
{
    protected $hidden = ['delete_time','status'];

    public function __construct($data = [])
    {
        parent::__construct($data);
    }


    /**
     * 处理图片前缀
     */
    protected function handleImgUrl($val)
    {
        $val = str_replace('\\', '/', $val);
        $arr = explode(';', $val);
        foreach ($arr as &$item){
            if(empty($item)) continue;
            $item = Request::domain().$item;
//            $item = config('APISetting.img_prefix').$item;
        }
        return $arr;
    }


    /**
     * 获取一条数据
     * @param array $where
     * @param string $field
     * @param string $order
     * @return Model
     */
    public function getOne($where=[],$field='*',$order=''){
        return self::where($where)
            ->order($order)
            ->field($field)
            ->find();
    }

    /**
     * 添加一条数据的公用方法
     * @url
     * @http
     * @param array $data
     * @return int|string
     */
    public function addData($data = []){
        return Db::name($this->getName())
            ->insert($data);
    }

    public function is_unique($name="",$id=0,$where=['status','neq',-1]){
        $data = [
            ['id','neq',$id],
            ['name','=',$name]
        ];
        array_push($data,$where);
        $result = $this->where($data)->find();
        if(!$result){
            return false;
        }
        return $data;
    }

    /**
     * 获取全部数据
     * @url
     * @http
     * @return mixed
     */
    public function findAll($where = [], $order='', $field = '*', $is_exist_delete=true){
        if($is_exist_delete){
            $where = array_merge(['status' => 1 ],$where);
        }
        return self::where($where)
            ->field($field)
            ->order($order)
            ->select();
    }

    /**
     * 获取指定字段的值
     * @param array $where
     * @param string $field
     * @return mixed
     */
    public function getValue($where = [], $field = '*'){
        return Db::name($this->getName())
            ->where($where)
            ->value($field);
    }


    /**
     * 修改数据的公用方法
     * @param array $where
     * @param array $data
     * @return int|string
     * @throws \think\Exception
     * @throws \think\exception\PDOException
     */
    public function saveData($data = [], $where = []){
        return Db::name($this->getName())
            ->where($where)
            ->update($data);
    }

    /**
     * 删除数据的公用方法
     * @url
     * @http
     * @param array $where
     * @return int
     */
    public function delData($where = []){
        return Db::name($this->getName())
            ->where($where)
            ->delete();
    }

    /**
     * 软删除
     */
    public function softDel($id=0){
        return self::get($id)
            ->delete();
    }


    /**
     * 设置字段自增
     * @param array $where
     * @param string $field
     * @param int $step
     * @return int|true
     * @throws \think\Exception
     */
    public function setIncData($where = [], $field = '', $step = 1){
        return Db::name($this->getName())
            ->where($where)
            ->setInc($field,$step);
    }

    /**
     * 设置字段自减
     * @param array $where
     * @param string $field
     * @param int $step
     * @return int|true
     * @throws \think\Exception
     */
    public function setDecData($where = [], $field = '', $step = 1){
        return Db::name($this->getName())
            ->where($where)
            ->setDec($field,$step);
    }


    /**
     *  获取面包屑链接
     */
    public static function getCrumb($cateId, $model){
        $cateTree = new Catetree();
        $parentArr = $cateTree->parentids($cateId, $model);
        return $parentArr;
    }


    /**
     * 获取子分类
     * @param string $field 添加字段值
     * @param $times 分类层级
     * @param int $pid 当前分类id
     * @param $resc_id 推荐位id
     * @return |null
     */
    public static function getCateJson($field = '', $times, $pid = 0, $resc_id = 0)
    {
        $arr = self::getACateData($field, [], $resc_id);
        $data = self::cateData($arr, $times, $pid);
        return $data;
    }

    public static function getACateData($fieldStr = '', $data = [], $resc_id = 0){
        $field = 'id,pid,name,' . $fieldStr;
        $where = [];
        if($resc_id){
            $where[] = ['','exp',Db::raw("FIND_IN_SET($resc_id, attributes)")];
        }
        $arr = self::field($field)
            ->order(['listorder' => 'desc', 'id' => 'desc'])
            ->where($where)
            ->where($data)
            ->select();
        return $arr;
    }

    /**
     *  分类生成无限极分类树
     */
    public static function cateData( $arr=[], $times, $pid = 0)
    {
        $cateTree = new Catetree();
        // 生成无限极分类树
        return $cateTree->hTree($arr, $pid, $times);
    }

    /**
     * 根据推荐获取数据
     */
    public static function getDataByResc($resc_id=0, $field='', $count=10)
    {
        $where[] = ['','exp',Db::raw("FIND_IN_SET($resc_id, attributes)")];
        $data = [
            'status' => 1
        ];
        $result = self::where($where)
            ->order('listorder desc')
            ->where($data)
            ->field($field)
            ->limit($count)
            ->select();
        return $result;
    }

}
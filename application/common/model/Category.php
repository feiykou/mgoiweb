<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/4/3 0003
 * Time: 下午 15:24
 */

namespace app\common\model;


use catetree\Catetree;
use think\Model;

class Category extends Model
{

    public function product()
    {
        return $this->belongsToMany('product', 'category_product', 'product_id', 'category_id');
    }

    protected function getMainImgUrlAttr($val, $data)
    {
        return $this->handleImgUrl($val);
    }

    protected function getMobileImgsUrlAttr($val, $data)
    {
        return $this->handleImgUrl($val);
    }

    private function handleImgUrl($val)
    {
        $val = str_replace('\\', '/', $val);
        $arr = explode(';', $val);
        foreach ($arr as &$item) {
            $item = config('APISetting.img_prefix') . $item;
        }
        return $arr;
    }

    protected static function init()
    {
        Category::afterInsert(function ($theme) {
            // 接收表单数据
            $themeData = input('post.');
            if (isset($themeData['product_ids'])) {
                $theme->product()->saveAll($themeData['product_ids']);
            }
        });

        Category::afterUpdate(function ($theme) {
            // 接收表单数据
            $themeData = input('post.');
            model('category_product')->where('category_id', $theme->id)->delete();
            if (isset($themeData['product_ids'])) {
                $theme->product()->saveAll($themeData['product_ids']);
            }
        });

        Category::beforeDelete(function ($theme) {
            model('category_product')->where('category_id', $theme->id)->delete();
            // 删除内存中的主图
            $main_img_url = $theme->main_img_url;
            if ($main_img_url) {
                if (is_array($main_img_url)) {
                    foreach ($main_img_url as $val) {
                        $delsrc = '/upload/images/' . $val;
                        if (file_exists($delsrc)) {
                            @unlink($delsrc);
                        }
                    }
                }

            }
            // 删除内存中的主图
            $head_img_url = $theme->mobile_imgs_url;
            if ($head_img_url) {
                if (is_array($head_img_url)) {
                    foreach ($head_img_url as $val) {
                        $delurl = '/upload/images/' . $val;
                        if (file_exists($delurl)) {
                            @unlink($delurl);
                        }
                    }
                }
            }
        });


    }

    // 获取栏目详情
    public function getCategoryDetail($id)
    {
        $products = self::hidden(['product.pivot'])
            ->with(['product' => function ($query) {
                $query->field('id,name,main_img_url,price');
            }])
            ->find($id);
        return $products;
    }

    // 获取分类下的所有数据
    public function getAllCate()
    {
        $data = [
            'status' => 1
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = self::where($data)->order($order)->select();
        return $result;
    }

    // 获取列表数据，并获取父类name
    public function getIndexCateData()
    {
        $cateData = self::alias('a1')
            ->field('a1.*,a2.name as pname')
            ->where(['a1.status' => 1])
            ->order([
                'a1.listorder' => 'desc',
                'a1.id' => 'desc'
            ])
            ->join('category a2', 'a1.pid=a2.id', 'left')
            ->paginate();
        return $cateData;
    }

    // 获取顶级分类，如果有$id并且是顶级分类，则排除
    public function getNormalFirstCate($id = 0)
    {
        $data = [
            ['status', '=', 1],
            ['pid', '=', 0],
            ['id', 'neq', $id]
        ];

        $order = [
            'id' => 'desc'
        ];

        return $this->where($data)
            ->order($order)
            ->select();
    }

    public function getColumnCate()
    {
        $data = [
            'status' => 1
        ];
        $columnData = $this->where($data)->select();
        $columnSortData = sortData($columnData);
        return $columnSortData;
    }

    // 获取当前栏目下的所有子分类
    public function getFirstCate($pid = 0)
    {
        $data = [
            ['pid', '=', $pid],
            ['status', 'neq', -1]
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $result = $this->where($data)
            ->order($order)
            ->paginate();
        return $result;
    }

    // 判断是否存在同名
    public function is_unique($name = "", $id = 0)
    {
        $data = [
            'status' => ['neq', -1],
            'id' => ['neq', $id],
            'name' => ['=', $name]
        ];
        $result = $this->where($data)->find();
        return $result;
    }


    public function getAllColumn()
    {
        $data = [
            'status' => 1,
            'pid' => 0,
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];
        $column = db("category")->where($data)->order($order)->select();
        foreach ($column as $key => $v) {
            $children = db("category")->where([
                'pid' => $v['id'],
                'status' => 1
            ])->select();
            if ($children) {
                $column[$key]['children'] = $children;
            } else {
                $column[$key]['children'] = 0;
            }
        }
        return $column;
    }


    public static function getCateJson($field = '', $times, $pid = 0)
    {
        $data = self::_cateData($field, $times, $pid);
        return $data;
    }


    private static function _cateData($fieldStr = '', $times, $pid = 0)
    {
        $cateTree = new Catetree();
        $field = "id,pid,name";
        $field .= ',' . $fieldStr;
        $arr = self::field($field)
            ->order(['listorder' => 'desc', 'id' => 'desc'])
            ->select()
            ->each(function ($data) {
            });
        // 生成无限极分类树
        return $cateTree->hTree($arr, $pid, $times);
    }

    public static function getWenData($cate_id, $type_id, $attr_id)
    {
        $data = [
            'pid' => $cate_id,
            'type' => $type_id
        ];
        $idData = self::where($data)
            ->where('','exp',"find_in_set($attr_id,attributes)")
            ->field('id')
            ->find();
        $result = [];
        if($idData){
            $result = self::where([
                'pid' => $idData['id']
            ])->where('','exp',"find_in_set($attr_id, attributes)")
                ->field('mobile_imgs_url,main_img_url,name,id,description,link_url')
                ->order([
                    'listorder' => 'desc',
                    'id' => 'desc'
                ])
                ->select();
        }
        return $result;
    }

    // 获取礼物推荐
    public static function getGiftData($cate_id, $type_id, $attr_id)
    {
        $data = [
            'pid' => $cate_id,
            'type' => $type_id
        ];
        $result = self::where($data)
            ->where('','exp',"find_in_set($attr_id,attributes)")
            ->field('id, name, mobile_imgs_url, main_img_url,link_url')
            ->find();

        return $result;
    }

    // 获取推荐产品
    public static function getProductData($cate_id, $type_id, $attr_id)
    {
        $data = [
            'pid' => $cate_id,
            'type' => $type_id
        ];
        $order = [
            'listorder' => 'desc',
            'id' => 'desc'
        ];

        $result = self::where($data)
            ->where('','exp',"find_in_set($attr_id,attributes)")
            ->order($order)
            ->with(['product' => function($query) {
                $query->field('id, name, price, main_img_url, description');
            }])
            ->hidden(['product.pivot'])
            ->field('id, name, mobile_imgs_url, main_img_url, description,link_url')
            ->select();
        return $result;
    }


}
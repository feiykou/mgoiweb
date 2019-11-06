<?php


namespace app\admin\controller;

use app\common\model\Banner as BannerModel;
use app\common\model\BannerItem as BannerItemModel;
use app\lib\exception\banner\BannerException;
use think\facade\Request;

class Banner extends Base
{
    /**
     * 列表页
     */
    public function index()
    {
        $result = BannerModel::select();
        return view('',[
            'bannerData' => $result
        ]);
    }

    /**
     * 添加页面
     */
    public function add ()
    {
        return view();
    }

    /**
     * 编辑数据页面
     *
     */
    public function edit ($id=0)
    {
        // 检查参数是否合法
        if(intval($id) < 1) $this->error("id参数不合法");
        $result = BannerModel::with(['items' => function($query) {
            $query->order('listorder desc');
        }])->get($id);
        if(!$result) $this->error("数据不存在");
        // 数据绑定
        $this->view->BannerData = $result;
        return view();
    }

    /**
     * 添加数据
     * @validate('BannerForm')
     */
    public function DoAdd()
    {
        $params = Request::post();
        BannerModel::create($params, true);
        return writeJson(201, [], '新增成功！');
    }

    /**
     * 更新数据
     * @validate('BannerForm.edit')
     */
    public function DoEdit($id=0)
    {
        $bannerInfo = Request::put();
        $banner = BannerModel::get($id);
        if(!$banner){
            throw new BannerException(['msg' => 'id为'.$id.'的轮播图不存在']);
        }
        $banner->allowField(true)->save($bannerInfo);
        return writeJson(201, [], '轮播图基础信息更新成功！');
    }

    /**
     * 删除轮播图元素
     * @param('ids','待删除的轮播图元素id列表','require|array|min:1')
     */
    public function delBanner()
    {
        $ids = Request::delete('ids');
        array_map(function ($id) {
            // 查询指定id的轮播图记录
            $banner = BannerModel::get($id, 'items');
            if(!$banner) throw new BannerException(['msg' => 'id为' . $id . '的轮播图不存在']);
            // 执行关联删除
            $banner->together('items')->delete();
        }, $ids);
        return writeJson(201, [], '删除成功');
    }


    /**
     * 添加轮播图元素数据
     * @validate('BannerItemForm')
     */
    public function addBannerItem()
    {
        $data = Request::post();
        BannerItemModel::create($data, true);
        return writeJson(201, [], '新增成功');
    }

    /**
     * 更新轮播图元素数据
     * @validate('BannerItemForm.edit')
     *
     * @param int $id
     * @return \think\response\Json
     * @throws BannerException
     */
    public function editBannerItem($id=0)
    {
        $bannerItemInfo = Request::patch();
        $bannerItem = BannerItemModel::get($id);
        if(!$bannerItem){
            throw new BannerException(['msg' => 'id为'.$id.'的轮播图元素不存在']);
        }
        $bannerItem->allowField(true)->save($bannerItemInfo);
        return writeJson(201, [], '更新成功！');
    }


    /**
     * 轮播图元素排序
     * @validate('Listorder')
     * @param $id
     * @param $listorder
     */
    public function listorder(){
        $params = Request::patch('listorder');
        $data = array_map(function ($v, $k) {
            return ['id'=>$k,'listorder'=>$v];
        }, $params, array_keys($params));
        $bannerItem = new BannerItemModel;
        $bannerItem->allowField(true)->saveAll($data);
        return writeJson(201, [], '排序完成！');
    }

    /**
     * 删除轮播图元素
     * @param('ids','待删除的轮播图元素id列表','require|array|min:1')
     */
    public function delBannerItem()
    {
        $ids = Request::delete('ids');
        // 传入多个id组成的数组进行批量删除
        BannerItemModel::destroy($ids);
        return writeJson(201, [], '删除成功');
    }

}
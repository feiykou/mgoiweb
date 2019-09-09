<?php


namespace app\common\model;

class BannerItem extends Common
{

    protected function getImgUrlAttr($val, $data)
    {
        return $this->handleImgUrl($val);
    }

    protected function getThumbUrlAttr($val, $data)
    {
        $val = str_replace('\\', '/', $val);
        return config('APISetting.img_prefix') . $val;
    }

    private function handleImgUrl($val)
    {
        $val = str_replace('\\', '/', $val);
        $arr = explode(';', $val);
        foreach ($arr as &$item){
            $item = config('APISetting.img_prefix').$item;
        }
        return $arr;
    }

    public static function getIndexBanner()
    {
        $data = [
            'banner_id' => 1
        ];
        $order = [
            'listorder' => 'desc'
        ];
        $result = self::where($data)->order($order)->select();
        return $result;
    }
}
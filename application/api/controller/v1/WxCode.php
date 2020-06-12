<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\AccessToken;
use think\Exception;
use think\facade\Request;

class WxCode extends BaseController
{
    public function wxValidate() {
        $param = Request::param();
        $signature = $param['signature'];
        $timestamp = $param['timestamp'];
        $nonce = $param['nonce'];
        $echostr = $param['echostr'];
        $token = '3901mgoi';
        $tmpArr = [$token, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return $echostr;
        }else{
            return false;
        }
    }
}
<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\AccessToken;
use think\Exception;
use think\facade\Request;

class Wx extends BaseController
{

    public function validate() {
        $param = Request::get();
        $signature = $param['signature'];
        $timestamp = $param['timestamp '];
        $nonce = $param['nonce'];

        $accessToken = new AccessToken();
        $token = $accessToken->get();
        if(!isset($token)) {
            throw new Exception('获取AccessToken异常');
        }
        $tmpArr = [$token, $timestamp, $nonce];
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );
        if( $tmpStr == $signature ){
            return true;
        }else{
            return false;
        }
    }
}
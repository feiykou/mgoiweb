<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;
use app\api\service\AccessToken;
use app\api\service\Ticket;
use app\api\validate\Wx;
use think\Exception;
use think\facade\Env;
use think\facade\Log;
use think\facade\Request;

class WxCode extends BaseController
{
    // 成为开发者
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
            return '';
        }
    }

    public function getConfigParam($url)
    {
        (new Wx())->goCheck('url');
        $ticketObj = new Ticket();
        $ticket = $ticketObj->get();
        if(!$ticket) {
            throw new Exception('临时票据不存在');
        }
        $rand = date('d') . substr(time(), -5) . substr(microtime(), 2, 5) . sprintf(
                '%02d', rand(0, 99));
        $time = time();
        $json = [
            "jsapi_ticket" => $ticket,
            "noncestr" => $rand,
            "timestamp" => $time,
            "url" => $url
        ];
        $jsonStr = implode('&', $json);
        $jsonStr = sha1( $jsonStr );
        return json([
            "signature" => $jsonStr,
            "nonceStr" => $rand,
            "timestamp" => $time,
            "appId" => config('wx.app_id'),
            "downloadUrl" => Request::url()
        ]);
    }

}
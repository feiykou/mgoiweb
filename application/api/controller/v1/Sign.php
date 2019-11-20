<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;

class Sign extends BaseController
{

    public function token()
    {
        $signature = input("signature");
        $timestamp = input("timestamp");
        $nonce = input("nonce");
        $echostr = input("echostr");
        if($this->checkSignature($signature,$timestamp,$nonce)){
            return $echostr;
        };
    }


    private function checkSignature($signature, $timestamp, $nonce)
    {
        $token = 'mgoi';
        $tmpArr = array($token, $timestamp, $nonce);
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
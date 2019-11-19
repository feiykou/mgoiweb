<?php


namespace app\api\controller\v1;


use app\api\controller\BaseController;

class Sign extends BaseController
{
    public function checkSignature()
    {
        $signature = input("signature");
        $timestamp = input("timestamp");
        $nonce = input("nonce");

        $token = 'mgoi';
        $tmpArr = array($token, $timestamp, $nonce);
        sort($tmpArr, SORT_STRING);
        $tmpStr = implode( $tmpArr );
        $tmpStr = sha1( $tmpStr );

        if( $tmpStr == $signature ){
            return input('echostr');
        }else{
            return false;
        }
    }
}
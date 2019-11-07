<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2019-04-17
 * Time: 22:53
 */

namespace app\api\behavior;


class CORS
{
    public function appInit()
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Headers: token,Origin, X-Requested-With, Content-Type, Accept");
        header('Access-Control-Allow-Methods: POST,GET');
        if(request()->isOptions()){
            exit();
        }
    }
}
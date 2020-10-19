<?php


namespace app\api\controller\v1;

use app\admin\controller\Base;
use app\common\model\Partner as PartnerModel;
use think\Exception;

class Partner extends Base
{
    public function createEmail($email)
    {
        try{
            (new \app\api\validate\Partner())->goCheck();
            if(PartnerModel::get(['email' => $email])){
                return writeJson(201,[],'ok');
            };
            PartnerModel::create(['email' => $email]);
            return writeJson(201,[],'ok');
        } catch(Exception $e){
            return writeJson(404,[],'error');
        }
    }
}
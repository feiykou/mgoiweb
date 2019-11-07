<?php
/**
 * @desc:
 * @author：feiy
 * @Date: 2017/10/23 16:29
 *
 */

namespace app\api\validate;


use app\lib\exception\ParameterException;
use think\facade\Request;
use think\Validate;

class BaseValidate extends Validate
{
    public function goCheck($scene=''){
        //必须设置contetn-type:application/json
        $params = Request::param();
        $params['token'] = Request::header('token');
        if(!empty($scene)){
            $result = $this->scene($scene)->check($params);
        }else{
            $result = $this->check($params);
        }
        if (!$result) {
            $exception = new ParameterException(
                [
                    // $this->error有一个问题，并不是一定返回数组，需要判断
                    'msg' => is_array($this->error) ? implode(
                        ';', $this->error) : $this->error,
                ]);
            throw $exception;
        }
        return true;


    }

    protected function isPositiveInteger($value,$rule="",$data="",$field=""){
        /*
         * 判断是否为正整数
         */
        if(is_numeric($value) && is_int($value + 0) && ($value + 0) > 0){
            return true;
        }
        else{
            return false;
        }
    }

    protected function isInteger($value, $rule='', $data='', $field='')
    {
        if (is_numeric($value) && is_int($value + 0) && ($value + 0) >= 0) {
            return true;
        }
        return $field . '必须是正整数包括零';
    }

    protected function isNotEmpty($value,$rule="",$data="",$field=""){
        /*
         * 判断是否为空
         */
        if(!empty($value)){
            return true;
        }
        else{
            return false;
        }
    }

    protected function isMobile($value){
        $rule = '^1(3|4|5|7|8)[0-9]\d{8}$^';
        $result = preg_match($rule,$value);
        if($result){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 功能：对客户端传递来的参数进行筛选
     *
     * @param $arrays  客户端传递来的参数
     */
    public function getDateByRule($arrays){
        if(array_key_exists('user_id',$arrays) | array_key_exists('uid',$arrays)){
            // 不允许包含user_id或者uid，防止恶意覆盖user_id外键
            throw new ParameterException([
                'msg' => '参数中包含有非法的参数名user_id或者uid'
            ]);
        }
        $newArray = [];
        foreach ($this->rule as $key => $value){
            $newArray[$key] = $arrays[$key];
        }
        return $newArray;
    }


}
<?php


namespace app\lib\exception;

use LinCmsTp5\exception\BaseException;

class ProductException extends BaseException
{
    public $code = 400;
    public $msg = '产品接口异常';
    public $error_code = '30000';
}
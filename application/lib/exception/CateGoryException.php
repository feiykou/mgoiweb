<?php


namespace app\lib\exception;

use LinCmsTp5\exception\BaseException;

class CateGoryException extends BaseException
{
    public $code = 400;
    public $msg = '栏目接口异常';
    public $error_code = '50000';
}
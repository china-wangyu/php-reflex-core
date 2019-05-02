<?php


namespace LinCmsPhp\exception;


use LinCmsTp5\exception\BaseException;

class ReflexException extends BaseException
{
    public $code = 400;
    public $msg = '反射的对象/方法注释错误';
    public $error_code = 66666;
}
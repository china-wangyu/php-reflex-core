<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2017/5/3
 * Time: 23:57
 */

namespace WangYu\exception;


class ReflexException extends BaseException
{
    public $code = 400;
    public $message = '反射的对象/方法注释错误';
    public $error_code = 66666;
}
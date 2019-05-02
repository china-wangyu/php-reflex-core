<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2017/5/3
 * Time: 23:57
 */

namespace WangYu\exception;


class ParseException extends BaseException
{
    public $code = 400;
    public $message = '解析数据时出现错误，请检查类·方法注释抒写方式';
    public $error_code = 66667;
}
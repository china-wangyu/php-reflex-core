<?php
/**
 * Created by User: wene<china_wangyu@aliyun.com> Date: 2019/7/3 Time: 10:00
 */

namespace WangYu\exception;


class ParseException extends BaseException
{
    public $code = 400;
    public $message = '解析数据时出现错误，请检查类·方法注释抒写方式';
    public $error_code = 66667;
}
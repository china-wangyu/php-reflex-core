<?php
/**
 * Created by User: wene<china_wangyu@aliyun.com> Date: 2019/7/3 Time: 10:00
 */

namespace WangYu\exception;


class ReflexException extends BaseException
{
    public $code = 400;
    public $message = '反射的对象/方法注释错误';
    public $error_code = 66666;
}
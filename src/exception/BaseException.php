<?php
/**
 * Created by User: wene<china_wangyu@aliyun.com> Date: 2019/7/3 Time: 10:00
 */

namespace WangYu\exception;


class BaseException extends \Exception
{
    //HTTP状态码
    public $code = 400;

    //错误具体信息
    public $message = '参数错误';

    //自定义的错误码
    public $error_code = 10000;

    public function __construct($params = [])
    {
        isset($params['code']) && $this->code = $params['code'];
        isset($params['message']) && $this->message = $params['message'];
        isset($params['error_code']) && $this->error_code = $params['error_code'];
        parent::__construct($this->error_code.$this->message,$this->code);
    }

}
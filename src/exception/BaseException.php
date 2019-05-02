<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2017/4/26
 * Time: 19:50
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
        if (!is_array($params)) {
            return;
        }
        if (array_key_exists('code', $params)) {
            $this->code = $params['code'];
        }
        if (array_key_exists('message', $params)) {
            $this->message = $params['message'];
        }
        if (array_key_exists('error_code', $params)) {
            $this->error_code = $params['error_code'];
        }
    }

}
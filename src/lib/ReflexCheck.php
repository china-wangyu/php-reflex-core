<?php
/** Created by 嗝嗝<china_wangyu@aliyun.com>. Date: 2019-09-20  */

namespace WangYu\lib;

/**
 * Trait ReflexCheck
 * @package WangYu\lib
 */
trait ReflexCheck
{

    public static function checkParamIsObject($param){
        if (!is_object($param)) {
            throw new  \Exception('获取注解内容失败，参数要求对象，你给的是' . gettype($param));
        }
    }

    public static function checkPHPVersion(){
        // 判断PHP版本是否大于等于7.1.0
        if (version_compare(PHP_VERSION,'7.1.0','<')){
            throw new \Exception('请安装PHP大于等于7.1.0的版本');
        }
    }


    public static function checkPHPEnv(){
        //判断是否开启加载文件函数注释
        if(intval(ini_get('opcache.save_comments')) > 1) {
            throw new \Exception('请修改php.ini配置：opcache.save_comments=1或直接注释掉此配置(无效请在 etc/php.d/ext-opcache.ini 文件中修改)');
        }
    }
}
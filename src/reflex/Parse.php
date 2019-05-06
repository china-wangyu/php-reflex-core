<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2017/5/3
 * Time: 23:57
 */

namespace WangYu\reflex;


use WangYu\exception\ParseException;

class Parse
{
    /**
     * @var string $reflexContent 反射注释内容
     */
    public $reflexContent;

    /**
     * @var mixed $data 筛选数据
     */
    public $data;

    /**
     * @var array $trims 注释提炼规则
     */
    private $trims = array('     ','  ', '/**', '*/', "\t", "\n", "\r", '$', '*');

    private $match = '/^%s+?\((\'?.+?\'?[,.+?]?)\)/is';



    public function __construct(string $reflexContent){
        try{
            $this->reflexContent =  $reflexContent;
        }catch (\Exception $exception){
            throw new ParseException(['message'=>'初始化反射解析类失败~']);
        }
    }

    public function get(string $noteName,array $noteKeys = [],string $rule = ''):array {
        !empty($rule) && $this->match = $rule;
        $commentArray = $this->parseReflexCommentToArray();
        $paramValue = $this->parseRouteParamComment($commentArray,$noteName);
        $this->data = $this->formatReflexParam($noteKeys,$paramValue);
        return $this->data;
    }

    /**
     * 清洗数据
     * @param string $reflexString 类方法注释
     * @return array
     */
    public function parseReflexCommentToArray(): array
    {
        if(empty($this->reflexContent)) return [];
        $newReflexString = str_replace($this->trims, '', trim($this->reflexContent));
        $reflexArray = explode('@', trim($newReflexString));
        return array_filter($reflexArray);
    }

    /**
     * 解析反射路由注释内容
     * @param array $comment
     * @param string $tag
     * @return array
     */
    protected function parseRouteParamComment(array $comment,string $tag):array {
        $argc = [];
        $match = sprintf($this->match,$tag);
        foreach ($comment as $item){
            if(!strstr($item,$tag))continue;
            preg_match($match,$item,$res,PREG_OFFSET_CAPTURE);
            if(!isset($res[1]))continue;
            $res = $res[1][0];
            $item = str_replace('\'','',trim($res));
            $item = explode(',',$item);
            $argc = array_merge([$item],$argc);
        }
        return $argc;
    }

    /**
     * 格式化反射参数
     * @param array $key
     * @param array $value
     * @return array
     */
    public function formatReflexParam(array $key,array $value):array {
        $argc = [];
        if (empty($value) or empty($key)) return $value;
        if (is_array($key[0])){
            foreach ($key as $keyItem){
                $argc = $this->setReflexParamKeys($value,$keyItem);
            }
        }else{
            $argc = $this->setReflexParamKeys($value,$key);
        }
        return $argc;
    }

    /**
     * 设置反射参数的键名
     * @param $item
     * @param $key
     * @return array|false
     */
    protected function setReflexParamKeys($item,$key){
        if(is_array($item[0])){
            return array_map(function ($i)use($key){
                if(count($key) == count($i)) return array_combine($key,$i);
            },$item);
        }elseif(count($key) == count($item)){
            return array_combine($key,$item);
        }
    }
}
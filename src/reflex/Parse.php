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

    /**
     * @var array $pattern 重置特殊规则
     */
    private $pattern = [
        'match' => '/\'(,)\'/i',
        'replace' => '\'~\''
    ];

    /**
     * @var string $match 注释内容清洗规则
     */
    private $match = '/^%s+?\((\'?.+?\'?[~.+?]?)\)/is';

    /**
     * @var string $delimiter 提取内容分隔符
     */
    private $delimiter = '~';

    public function __construct(string $reflexContent){
        try{
            $this->reflexContent =  $reflexContent;
        }catch (\Exception $exception){
            throw new ParseException(['message'=>'初始化反射解析类失败~']);
        }
    }

    /**
     * 获取
     * @param string $noteName
     * @param array $noteKeys
     * @param string $rule
     * @return array
     * @throws \Exception
     */
    public function get(string $noteName,array $noteKeys = [],string $rule = ''):array {
        try{
            !empty($rule) && $this->match = $rule;
            $commentArray = $this->parseReflexCommentToArray();
            $paramValue = $this->parseRouteParamComment($commentArray,$noteName);
            $this->data = $this->formatReflexParam($noteKeys,$paramValue);
            return $this->data;
        }catch (\Exception $exception){
            throw new \ParseException(['message'=>$exception->getMessage()]);
        }
    }

    /**
     * 清洗数据
     * @return array
     * @throws \Exception
     */
    public function parseReflexCommentToArray(): array
    {
        try{
            if(empty($this->reflexContent)) return [];
            if (strstr($this->reflexContent,'~')){
                throw new \Exception('请不要在注释内容添加‘~’符号，‘~’会影响注释内容提取');
            }
            $newReflexString = str_replace($this->trims, '', trim($this->reflexContent));
            $reflexArray = explode('@', trim($newReflexString));
            return array_filter($reflexArray);
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 解析反射路由注释内容
     * @param array $comment
     * @param string $tag
     * @return array
     * @throws \Exception
     */
    protected function parseRouteParamComment(array $comment,string $tag):array {
        try{
            $argc = [];
            $match = sprintf($this->match,$tag);
            foreach ($comment as $item){
                if(!strstr($item,$tag))continue;
                $item = preg_replace($this->pattern['match'], $this->pattern['replace'], $item);
                preg_match($match,$item,$res,PREG_OFFSET_CAPTURE);
                if(!isset($res[1]))continue;
                $res = $res[1][0];
                $item = str_replace('\'','',trim($res));
                $item = explode($this->delimiter,$item);
                $argc = array_merge([$item],$argc);
            }
            return $argc;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 格式化反射参数
     * @param array $key
     * @param array $value
     * @return array
     * @throws \Exception
     */
    public function formatReflexParam(array $key,array $value):array {
        try{
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
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 设置反射参数的键名
     * @param $item
     * @param $key
     * @return array|false
     * @throws \Exception
     */
    protected function setReflexParamKeys($item,$key){
        try{
            if(is_array($item[0])){
                return array_map(function ($i)use($key){
                    if(count($key) == count($i)) return array_combine($key,$i);
                },$item);
            }elseif(count($key) == count($item)){
                return array_combine($key,$item);
            }
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
    }
}
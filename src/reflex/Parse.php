<?php


namespace LinCmsPhp\reflex;


use LinCmsTp5\exception\ParseException;

class Parse
{
    /**
     * @var \ReflectionMethod $linReflex 反射对象
     */
    public $linReflex;

    /**
     * @var mixed $data 筛选数据
     */
    public $data;

    /**
     * @var array $trims 注释提炼规则
     */
    private $trims = array('     ','  ', '/**', '*/', "\t", "\n", "\r", '$', '*');

    private $match = '/^%s+?\((\'?.+?\'?[,.+?]?)\)/is';



    public function __construct(\ReflectionMethod $reflectionMethod){
        try{
            $this->linReflex =  $reflectionMethod;
        }catch (\Exception $exception){
            throw new ParseException(['msg'=>'初始化反射解析类失败~']);
        }
    }

    public function get(string $noteName,array $noteKeys = [],string $rule = ''):array {
        !empty($rule) && $this->match = $rule;
        $comment = $this->linReflex->getDocComment();
        $commentArray = $this->parseReflexCommentToArray($comment);
        $paramValue = $this->parseRouteParamComment($commentArray,$noteName);
        $this->data = $this->formatReflexParam($noteKeys,$paramValue);
        return $this->data;
    }

    /**
     * 清洗数据
     * @param string $reflexString 类方法注释
     * @return array
     */
    public function parseReflexCommentToArray(string $reflexString): array
    {
        if(empty($reflexString)) return [];
        $newReflexString = str_replace($this->trims, '', trim($reflexString));
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
        if (empty($value)) return $value;
        if (is_array($key[0])){
            foreach ($key as $keyItem){
                if(is_array($value[0])){
                    $argc[] = array_map(function ($item)use($keyItem){
                        if(count($keyItem) == count($item)) return array_combine($keyItem,$item);
                    },$value);
                }elseif(count($key) == count($value)){
                    $argc = array_combine($key,$value);
                }
            }
        }
        if(count($key) == count($value)) return array_combine($key,$value);
        return $argc;
    }

}
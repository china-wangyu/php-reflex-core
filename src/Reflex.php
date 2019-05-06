<?php
/**
 * Created by PhpStorm.
 * User: 沁塵
 * Date: 2017/5/3
 * Time: 23:57
 */

namespace WangYu;


use WangYu\exception\ReflexException;
use WangYu\reflex\Parse;
use WangYu\reflex\Reflex as ReflexModle;

class Reflex
{
    /** @var object $object 用户传递类的实例 */
    protected $object;

    /**
     * @var \ReflectionClass $reflex 用户传递类的反射实例
     */
    protected $reflex;

    /**
     * @var Parse $parse
     */
    protected $parse;

    /**
     * Reflex constructor.
     * @param string|object $object 类命名空间，或者一个对象
     * @param string $action 对象方法
     * @throws ReflexException
     */
    public function __construct($object,string $action = '')
    {
        try{
            if ($object instanceof  \ReflectionClass) $object = $object->getName();
            is_string($object) && $object = new $object();
            $this->setObject($object);
            if (!empty($action))$this->setAction($action);
        }catch (\Exception $exception){
            throw new ReflexException();
        }
    }

    # 设置类反射解析
    public function setObject(object $object):self {
        try {
            $this->object = $object;
            $this->reflex = new ReflexModle($this->object);
            $reflexDoc = $this->reflex->getDocComment();
            $this->parse = new Parse($reflexDoc);
            return $this;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    # 设置方法反射解析
    public function setAction(string $action):self {
        try{
            if(!method_exists($this->object,$action)) throw new \Exception('类的方法·'.$action.'不存在');
            $reflexDoc = $this->reflex->getMethod($action)->getDocComment();
            $this->parse = new Parse($reflexDoc);
            return $this;
        }catch (\Exception $exception){
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * 注释名称
     * @param string $noteName 反射出的注释内容，取用的参数名称
     * @param array $noteKeys 参数值的数组keys
     * @param string $rule 解析规则
     * @inheritDoc
     * 反射的方法内容，自动格式化数据后会变成一个数组
    注释内容：
    /**
     * @route('rule','method')
     * @param('name','doc','rule')
     *\/
    格式如下：
    array{
    0 => 'route('rule','method')',
    0 => 'param('name','doc','rule')',
    }
     * @inheritDoc 因此希望大家不要盲目的设置rule,默认就可以了
     */
    public function get(string $noteName,array $noteKeys = [],string $rule = ''){
        return $this->parse->get($noteName,$noteKeys,$rule);
    }
}
<?php
/** Created By wene<china_wangyu@aliyun.com>, Data: 2019/7/16 */


namespace WangYu;

use WangYu\lib\ReflexAnalysis;

final class Reflex
{
    /**
     * @var \ReflectionClass $Reflection
     */
    protected $rc;

    /**
     * @var \ReflectionMethod
     */
    protected $rm;

    /**
     * @var ReflexAnalysis $analyse 解析器
     */
    protected $analyse;

    /**
     * Reflex constructor.
     * @param $object
     * @throws \Exception
     */
    public function __construct($object)
    {
        if (!is_object($object)) {
            throw new  \Exception('获取注解内容失败，参数要求对象，你给的是' . gettype($object));
        }
        try {
            $this->rc = new \ReflectionClass($object);
            $this->analyse = new ReflexAnalysis($this->rc->getDocComment());
        } catch (\Exception $exception) {
            throw new  \Exception(get_class($object) . '类不存在');
        }
    }

    /**
     * 设置方法
     * @param string $method
     * @return Reflex
     * @throws \Exception
     */
    public function setMethod(string $method): self
    {
        try {
            $this->rm = $this->rc->getMethod($method);
            $this->analyse = new ReflexAnalysis($this->rm->getDocComment());
            return $this;
        } catch (\Exception $exception) {
            throw new  \Exception($this->rc->getName() . '类不存在');
        }
    }


    /**
     * 获取自定义注解内容
     * @param string $func 注解函数名称
     * @param mixed $keys 注解函数的解析规则，例如字符串 rule ,例如数组 ['rule','module','show']
     * @return array
     * @throws \Exception
     */
    public function get(string $func, $keys = null){
        return $this->analyse->get($func, $keys);
    }
}
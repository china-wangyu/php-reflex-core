<?php
/** Created By wene<china_wangyu@aliyun.com>, Data: 2019/7/16 */


namespace WangYu\lib;

/**
 * Class AnnotationAnalyse 注解解析规则
 * @package WangYu\annotation\lib
 */
class ReflexAnalysis
{
    /**
     * @var string $annotation 注解内容
     */
    private $annotation;

    /**
     * @var array $trims 注释提炼规则
     */
    private $trims = array('     ', '  ', '/**', '*/', "\t", "\n", "\r", '$', '*');

    /**
     * @var array $pattern 重置特殊规则
     */
    private $pattern = [
        'match' => '/\'(,)\'/i',
        'replace' => '\'`\''
    ];

    /**
     * @var string $match 注释内容清洗规则
     */
    private $match = '/^%s+?\((\'?.+?\'?[%s.+?]?)\)/is';

    /**
     * @var string $delimiter 提取内容分隔符
     */
    private $delimiter = '`';

    /**
     * @var string $mark 函数标识
     */
    private $mark = '@';

    /**
     * @var mixed $data 返回值
     */
    protected $data;

    /**
     * @var string  注解函数名称
     */
    protected $func;

    /**
     * @var mixed $keys 注解keys
     */
    protected $keys;

    public function __construct(string $annotation)
    {
        $this->annotation = $annotation;
    }

    /**
     * 获取解析结果
     * @param string $func 注解函数
     * @param array $keys 注解结果集 keys
     * @return array
     * @throws \Exception
     */
    public function get(string $func, $keys)
    {
        $this->func = $func;
        $this->keys = $keys;
        $this->trim();
        $this->toArray();
        $this->regular();
        $this->format();
        return $this->data;
    }

    /**
     * @step1 清除空格等多余字符
     * @throws \Exception
     */
    private function trim()
    {
        try {
            if (empty($this->annotation)) return;
            if (strstr($this->annotation, $this->delimiter)) {
                throw new \Exception('请不要在注释内容添加‘' . $this->delimiter . '’符号，‘' . $this->delimiter . '’会影响注释内容提取');
            }
            $this->data = str_replace($this->trims, '', trim($this->annotation));
        } catch (\Exception $exception) {
            throw new \Exception('@step1.清除空格等多余字符.' . $exception->getMessage());
        }
    }

    /**
     * @step2 字符串转数组
     * @throws \Exception
     */
    private function toArray()
    {
        try {
            if (!is_string($this->data))return;
            $this->data = array_filter(explode($this->mark, trim($this->data)));
        } catch (\Exception $exception) {
            throw new \Exception('@step2.字符串转数组.' . $exception->getMessage());
        }
    }

    /**
     * @step3 正则匹配
     * @throws \Exception
     */
    private function regular()
    {
        try {
            if (empty($this->data) or !is_array($this->data))return;
            $argc = [];
            $match = sprintf($this->match, $this->func, $this->delimiter);
            foreach ($this->data as $item) {
                if (!strstr($item, $this->func)) continue;
                $item = preg_replace($this->pattern['match'], $this->pattern['replace'], $item);
                preg_match($match, $item, $res, PREG_OFFSET_CAPTURE);
                if (!isset($res[1])) continue;
                $item = str_replace('\'', '', trim($res[1][0]));
                $item = explode($this->delimiter, $item);
                $argc = array_merge([$item], $argc);
            }
            $this->data = $argc;
        } catch (\Exception $exception) {
            throw new \Exception('@step3.正则匹配.' . $exception->getMessage());
        }
    }

    /**
     * @step4 格式化
     */
    private function format()
    {
        try {
            if (empty($this->data)) return;
            if (empty($this->keys)) {
                $this->data = $this->data[0];
                return;
            }
            if (isset($this->data[1])) {
                foreach ($this->data as &$item) {
                    $item = $this->sort($item);
                }
            } else {
                $this->data = $this->sort($this->data[0]);
            }
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }

    /**
     * @step5 排序内容
     * @param array $item
     * @return array|false|mixed|string
     * @throws \Exception
     */
    private function sort(array $item)
    {
        try {
            $result = [];
            if (empty($item)) return '';
            if (!isset($item[1])) return $item[0];
            if (is_array($item)) {
                if (count($this->keys) == count($item)) return array_combine($this->keys, $item);
                foreach ($this->keys as $index => $value) {
                    $result[$value] = $item[$index] ?? '';
                }
            }
            return $result;
        } catch (\Exception $exception) {
            throw new \Exception($exception->getMessage());
        }
    }
}
# lin-cms-php-reflex-core
lin-cms-php  核心反射类封装

# `composer` 安装

```composer
composer require wangyu/reflex-core
```

# 使用方法

## 首先引入命名空间`use WangYu\Reflex`

```php
use WangYu\Reflex;
```

## 然后实例化类

```php
$reflex = Reflex($object,$action);
```

## 最后获取对应的方法反射文档数据

> 如果想获取下面的内容,方法的注释应当这样写


**`注释举例：`**
```php
/**
 * 查询指定bid的图书
 * @route('v1/book/:bid','get')
 * @param Request $bid
 * @param('bid','bid的图书','require')
 * @return mixed
 */
public function getBook($bid)
{
    $result = BookModel::get($bid);
    return $result;
}
```

**`获取：`**

```php
$route = $reflex->get('route',['rule','method']);
```

**`结果：`**

```php
$route = {
    'rule' => '/v1/book/',
    'method' => 'get'
}
```

# 联系我们

- QQ: `354007048` 
- Email: `china_wangyu@aliyun.com`
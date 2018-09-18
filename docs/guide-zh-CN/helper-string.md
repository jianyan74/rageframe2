## 字符串操作辅助类

目录

- uuid 生成
- 日期和时间戳互转
- 获取缩略图地址
- 创建缩略图地址
- 分析枚举类型配置值
- 返回字符串在另一个字符串中第一次出现的位置
- XML 字符串转对象
- 字符串提取汉字
- 字母大小写转驼峰命名
- 驼峰命名法转下划线风格
- 获取字符串后面的字符串
- 获取随机字符串
- 获取随机数字

引入

```
use common\helpers\StringHelper;
```

### uuid 生成

```
/**
 * 生成Uuid
 * 
 * @param string $type 类型 默认时间 time/md5/random/sha1/uniqid 其中uniqid不需要特别开启php函数
 * @param string $name 加密名
 * @return string
 */
StringHelper::uuid($type = 'time', $name = 'php.net');
```

### 日期和时间戳互转

```
/**
 * 日期和时间戳互转
 *
 * 说明：如果是日期转时间戳，如果是时间就转日期
 *
 * @param string|int $value
 * @param string $rule 日期规则
 * @return false|int|string
 */
StringHelper::dateIntTransition($value, $rule = 'Y-m-d H:i:s')
```

### 获取缩略图地址

### 创建缩略图地址

### 分析枚举类型配置值

### 返回字符串在另一个字符串中第一次出现的位置

### XML 字符串转对象

### 字符串提取汉字

### 字母大小写转驼峰命名

### 驼峰命名法转下划线风格

### 获取字符串后面的字符串

### 获取随机字符串

### 获取随机数字
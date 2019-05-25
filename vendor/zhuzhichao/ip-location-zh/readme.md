# Ip Location Zh
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/zhuzhichao/ip-location-zh/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/zhuzhichao/ip-location-zh/?branch=master)
[![Build Status](https://api.travis-ci.org/zhuzhichao/ip-location-zh.svg?branch=master)](https://travis-ci.org/zhuzhichao/ip-location-zh)

数据最后更新时间为 2018-7-2, 更新时间:不定期。 数据源于: http://www.ipip.net

IPIP典型客户
![ipip客户](http://img.zhuzhichao.com/ipipservercom.png)

这是一个能够通过 Ip，获取该 IP 所在的位置，例如通过 ip：`171.12.10.156` 可以获得是 `中国河南郑州`，以及中国行政区划代码(支持到市级), 同样能获得国外的地址，但是没有国内详细。

>注意: 该免费数据库不提供经纬度、运营商、行政区等更详细的内容, 如果你有更多的需求可以考虑使用他们的收费服务。本包的目的是提供给需求简单, 手里没钱的程序员或者老板不舍得掏钱来获取定时更新的IP信息

>国家码和邮编不是该包负责的内容, 需自行构建相关的程序


## 特点

1. 不配置和使用数据库，妈妈再也不用担心配置问题了
2. 使用简单，功能专（dān）注（yī）
3. 使用 [composer](https://getcomposer.org/) 进行安装管理，国际标准，方便快捷，即安即用，随时更新数据库

## Install

这里不详细介绍安装 composer 了，大家根据 [链接](https://getcomposer.org/) 自行安装吧！

`composer require "zhuzhichao/ip-location-zh"`

## Usage

#### Common

可以这样来用

```php
require 'vendor/autoload.php';  
use Zhuzhichao\IpLocationZh\Ip;  
var_dump(Ip::find('171.12.10.156'));
```

```
// 返回结果
array (size=4)
  0 => string '中国' (length=6)
  1 => string '河南' (length=6)
  2 => string '郑州' (length=6)
  3 => string '' (length=0)
  4 => string '410100' (length=6)
```

#### Laravel

对于`laravel`可以这样优雅的用:

1.安装该插件

2.在 `config/app.php`(Laravel 5.0 - 5.4)添加下面的代码，如果是 Laravel 5.5+ ，已经支持扩展包发现，不需要添加下面的代码

```php
// Laravel 5.5 不需要添加
'aliases' => [
    'Ip'  => 'Zhuzhichao\IpLocationZh\Ip', 
],
```

3.然后开始在你的项目里面使用了 `Ip::find('171.12.10.156')` 或 `Ip::find(Request::getClientIp())`


**对，很简单，只用一个方法，那就是 `find`**

## Contributing
有什么新的想法和建议，欢迎提交 [issue](https://github.com/zhuzhichao/ip-location-zh/issues) 或者 [Pull Requests](https://github.com/zhuzhichao/ip-location-zh/pulls) 。

## License
MIT


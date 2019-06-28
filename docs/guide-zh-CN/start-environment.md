## 环境搭建

目录

- Windows环境
- Windows微信开发环境
- Linux环境
- Mac环境

### Windows环境

在Windows环境下，建议开发者使用phpStudy环境套件，下面引用官方的介绍：
该程序包集成最新的Apache+Nginx+LightTPD+PHP+MySQL+phpMyAdmin+Zend Optimizer+Zend Loader，一次性安装，无须配置即可使用，是非常方便、好用的PHP调试环境。该程序绿色小巧简易迷你仅有32M，有专门的控制面板。总之学习PHP只需一个包。
对学习PHP的新手来说，WINDOWS下环境配置是一件很困难的事；对老手来说也是一件烦琐的事。因此无论你是新手还是老手，该程序包都是一个不错的选择。
全面适合 Win2000/XP/2003/win7/win8/win2008 操作系统 ,支持Apache、IIS、Nginx和LightTPD。
新特征：完美支持win10，支持自定义php版本
下载地址：[phpStudy](http://www.phpstudy.net/)

### Windows微信开发环境

1、微信开发调试会有缓存问题导致无法使用获取用户信息。原因默认的C:\Windows目录无写入权限。调整php.ini中的sys_temp_dir路径即可。

> wechat SDK 遵循了官方建议，所以在调用这些接口时，除了按照官方文档设置操作证书文件外，还需要保证服务器正确安装了 CA 证书。

2、下载 CA 证书

你可以从 [http://curl.haxx.se/ca/cacert.pem](http://curl.haxx.se/ca/cacert.pem) 下载 或者 使用微信官方提供的证书中的 CA 证书 [rootca.pem](https://pay.weixin.qq.com/wiki/doc/api/app/app.php?chapter=9_1) 也是同样的效果。在 php.ini 中配置 CA 证书

3、只需要将上面下载好的 CA 证书放置到您的服务器上某个位置，然后修改 php.ini 的 curl.cainfo 为该路径（绝对路径！），重启 php-fpm 服务即可。

```
curl.cainfo = /path/to/downloaded/cacert.pem
```

>  注意证书文件路径为绝对路径！以自己实际情况为准。

其它修改 HTTP 类源文件的方式是不允许的。

### Linux环境

- 使用[宝塔面板](https://www.bt.cn/)，该面板使用起来简单，功能强大。
- 使用[LNMP](https://lnmp.org/)一键安装包
- 使用[docker安装](https://github.com/jianyan74/lnmp-dockerfiles)

### Mac环境

在Mac环境下，推荐使用[mamp](https://www.mamp.info/en/)
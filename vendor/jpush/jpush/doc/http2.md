# JPush API PHP Client With HTTP/2 Support

> JPush API PHP Client 全面支持 HTTP/2，
> **要求 PHP >= 5.5.24**，
> 但由于 libcurl 对于 HTTP/2 的实现依赖于第三方库 [nghttp2](https://github.com/nghttp2/nghttp2) 所以如果要支持 HTTP/2 需要做一些其他的配置。

### 安装 nghttp2

系统依赖仅针对 Ubuntu 14.04 LTS (trusty) 和 Debian 7.0 (wheezy) 或以上版本，其他系统版本请按照 nghttp2 的文档来操作:

> From Ubuntu 15.10, spdylay has been available as a package named libspdylay-dev. For the earlier Ubuntu release, you need to build it yourself: http://tatsuhiro-t.github.io/spdylay/

详细情况请查看 [nghttp2 的文档](https://github.com/nghttp2/nghttp2#requirements)。

```bash
# Get build requirements
# Some of these are used for the Python bindings
# this package also installs
$ sudo apt-get install g++ make binutils autoconf automake autotools-dev libtool pkg-config \
  zlib1g-dev libcunit1-dev libssl-dev libxml2-dev libev-dev libevent-dev libjansson-dev \
  libjemalloc-dev cython python3-dev python-setuptools

# Build nghttp2 from source
$ git clone https://github.com/tatsuhiro-t/nghttp2.git
$ cd nghttp2
$ autoreconf -i
$ automake
$ autoconf
$ ./configure
$ make
$ sudo make install
```

### 升级 curl 至最新版本

```bash
$ sudo apt-get build-dep curl
# 请根据当前的 curl 官网中的最新版本(https://curl.haxx.se/download/)替换下面的相应位置
$ wget https://curl.haxx.se/download/curl-7.x.x.tar.bz2
$ tar -xvjf curl-7.x.x.tar.bz2
$ cd curl-7.x.x
$ ./configure --with-nghttp2=/usr/local --with-ssl
$ make
$ sudo make install
```

### 测试

##### 命令行测试
命令行运行 `$ curl --version`，若输出中的 Features 栏中有 `HTTP2` 一项则证明配置成功。

##### 样例测试
运行样例 `$ php examples/devices/get_devices_example.php`，若输出中的 HTTP 版本是 HTTP/2 则证明已经在使用 HTTP2 发送请求和接受响应了。

##### 测试测试
运行测试 `$./vendor/bin/phpunit tests/JPush/DevicePayloadTest.php`，若打印出的 http headers 中的 HTTP 版本是 HTTP/2 则证明已经在使用 HTTP2 发送请求和接受响应了。

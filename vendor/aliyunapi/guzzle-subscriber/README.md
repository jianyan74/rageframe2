# aliyun-guzzle

鉴于官方SDK被称为史上最烂外包SDK，所以这个中间件是 GuzzleHttp 专用的，支持阿里云大部分API请求。有需要的自行扩展。
For license information check the [LICENSE](LICENSE)-file.

[![Latest Stable Version](https://poser.pugx.org/aliyunapi/guzzle-subscriber/v/stable.png)](https://packagist.org/packages/aliyunapi/guzzle-subscriber)
[![Total Downloads](https://poser.pugx.org/aliyunapi/guzzle-subscriber/downloads.png)](https://packagist.org/packages/aliyunapi/guzzle-subscriber)

Installation
------------

The preferred way to install this extension is through [composer](http://getcomposer.org/download/).

Either run

```
php composer.phar require --prefer-dist aliyunapi/guzzle-subscriber
```

or add

```
"aliyunapi/guzzle-subscriber": "~1.0"
```

to the require section of your composer.json.

使用
------------
````
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use aliyun\guzzle\subscriber\Rpc;

$stack = HandlerStack::create();

//跟guzzlephp普通用法唯一的区别就是这里吧中间件加载进来，他会自动帮你签名重新包装请求参数。
$middleware = new Rpc([
    'accessKeyId' => '123456',
    'accessSecret' => '654321',
]);
$stack->push($middleware);

//这里设置 网关地址，数组参数请参见 http://docs.guzzlephp.org/en/latest/request-options.html 
//操作哪个接口对应的 base_uri 就写哪个
$client = new Client([
    'base_uri' => 'http://live.aliyuncs.com/',
    'handler' => $stack,
]);

//查询参数  https://help.aliyun.com/document_detail/35412.html 
//这个页面列出了几个参数就在数组提交几个参数,其他的API接口也一样，只需对应参数给他提交即可。
$res = $client->get('/', [
    'query' => [
        'Action' => 'DescribeLiveStreamOnlineUserNum',
        'DomainName' => 'live.aaa.tv',
        'AppName' => 'live',
        'StreamName' => 'bbb',
        ]
]);

print_r($res->getBody()->getContents());

////////////////////////////////////////////////////////////////////ROA已经实现了，但是没有条件测试，欢迎提交合并
use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use aliyun\guzzle\subscriber\Roa;

$stack = HandlerStack::create();

//跟guzzlephp普通用法唯一的区别就是这里吧中间件加载进来，
//他会自动帮你签名重新包装请求参数。
$middleware = new Roa([
    'accessKeyId' => '123456',
    'accessSecret' => '654321',
    'version'=>'123456',
]);
$stack->push($middleware);

$client = new Client([
    'base_uri' => 'http://cs.aliyuncs.com/',
    'handler' => $stack,
]);

$res = $client->get('/', [
    'query' => [
        //etc
        ]
]);

print_r($res->getBody()->getContents());
````


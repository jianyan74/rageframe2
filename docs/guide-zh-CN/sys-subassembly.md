## 系统组件

目录

- 基本组件
  - 获取单个配置信息
  - 获取全部配置信息
  - 打印调试
  - 行为日志记录
  - 微信接口验证及报错获取
  - 解析 model 报错
- 文件上传
- 生成二维码
- IP地址转地区
- 快递查询
- Curl
- 中文转拼音
- 爬虫
- Glide

### 基本组件

读取后台的配置

```
// 后台配置
Yii::$app->debris->backendConfig($fildName);

// 强制不从缓存读取配置
Yii::$app->debris->backendConfig($fildName, true);

// 强制不从缓存读取所有配置
Yii::$app->debris->backendConfigAll(true);
```

读取商户端的配置

```
// 商户端配置
Yii::$app->debris->merchantConfig($fildName);

// 强制不从缓存读取配置
Yii::$app->debris->merchantConfig($fildName, true);

// 强制不从缓存读取所有配置
Yii::$app->debris->merchantConfigAll(true);
```

自动读取对应配置信息

> 不了解其机制的话请谨慎使用  
> 规则：如果有 merchant_id 的话，则直接读取后台配置，没有的话会去读取商户端配置

```
// 注意$fildName 为你的配置标识,默认从缓存读取
Yii::$app->debris->config($fildName);

// 强制不从缓存读取
Yii::$app->debris->config($fildName, true);

// 从缓存中强制读取商户 ID 为 1 配置(注意: 1 为总后台的 ID)
Yii::$app->debris->config($fildName, false);
```

##### 获取全部配置信息

```
// 注意默认从缓存读取
Yii::$app->debris->configAll();

// 强制不从缓存读取
Yii::$app->debris->configAll(true);

// 从缓存中强制读取商户 ID 为 1 全部配置(注意: 1 为总后台的 ID)
Yii::$app->debris->configAll(false, 1);
```

读取某一端的配置

```
// 商户端配置
Yii::$app->debris->merchantConfigAll();
```

##### 打印调试

```
Yii::$app->debris->p();
```

##### 行为日志记录

```
/**
 * 行为日志
 *
 * @param string $behavior 行为标识
 * @param string $remark 备注 注意长度为255
 * @param bool $noRecordData 是否记录 post 数据 [true||false]
 * @throws \yii\base\InvalidConfigException
 */
Yii::$app->services->actionLog->create($behavior, $remark, $noRecordData)
```

##### 微信接口验证及报错

```
// 默认直接报错
Yii::$app->debris->getWechatError($message);

// 如果想不直接报错并返回报错信息
$error = Yii::$app->debris->getWechatError($message, false);
```

##### 解析 model 报错

```
// 注意 $firstErrors 为 $model->getFirstErrors();
Yii::$app->debris->analyErr($firstErrors);
```

### 文件上传

获取组件全部实现 `League\Flysystem\Filesystem` 接口

```
// 支持 oss/cos/qiniu/local, 配置不传默认使用总后台
$entity = Yii::$app->uploadDrive->local($config)->entity()；
```

使用案例

```
$entity = Yii::$app->uploadDrive->local()->entity()；
$stream = fopen('文件绝对路径', 'r+');
$result = $entity->writeStream('存储相对路径', $stream);

// 直接写入base64数据
$entity->write('存储相对路径', $base64Data);
```

更多说明：新增驱动请放入 `common\components\uploaddrive` 目录, 并在 `common\components\UploadDrive` 类内实现可实例化的方法

### 生成二维码

```
$qr = Yii::$app->get('qr');
Yii::$app->response->format = Response::FORMAT_RAW;
Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

return $qr->setText('www.rageframe.com')
    ->setLabel('2amigos consulting group llc')
    ->setSize(150)
    ->setMargin(7)
    ->writeString();
```
or

```
use Da\QrCode\QrCode;

$qrCode = (new QrCode('This is my text'))
    ->setSize(250)
    ->setMargin(5)
    ->useForegroundColor(51, 153, 255);

// 把图片保存到文件中:
$qrCode->writeFile(Yii::getAlias('@attachment') . '/code.png'); // 没有指定的时候默认为png格式

// 直接显示在浏览器 
header('Content-Type: '.$qrCode->getContentType());
echo $qrCode->writeString();
```

[二维码文档](http://qrcode-library.readthedocs.io/en/latest/)

### IP地址转地区

```
use Zhuzhichao\IpLocationZh\Ip;

var_dump(Ip::find('171.12.10.156'));
```

输出结果

```
array (size=4)
  0 => string '中国' (length=6)
  1 => string '河南' (length=6)
  2 => string '郑州' (length=6)
  3 => string '' (length=0)
  4 => string '410100' (length=6)
```

### 快递查询

```
// 查询所有的可用快递公司
$companies = Yii::$app->logistics->companies('aliyun');

/**
 * 支持 aliyun(阿里云)、juhe(聚合)、kdniao(快递鸟)、kd100(快递100)
 *
 * @param string $no 快递单号
 * @param null $company 快递公司
 * @param bool $isCache 是否缓存读取默认缓存1小时
 * @return OrderInterface
 */
$order = Yii::$app->logistics->aliyun($no, $company, $isCache);
```

更多操作

```
 $order->getCode(); // 状态码
 $order->getMsg(); // 状态信息
 $order->getCompany(); // 物流公司简称
 $order->getNo(); // 物流单号
 $order->getStatus(); // 当前物流单状态
 
 注：物流状态可能不一定准确
 
 $order->getDisplayStatus(); // 当前物流单状态展示名
 $order->getAbstractStatus(); // 当前抽象物流单状态
 $order->getCourier(); // 快递员姓名
 $order->getCourierPhone(); // 快递员手机号
 $order->getList(); // 物流单状态详情
 $order->getOriginal(); // 获取接口原始返回信息
```

### Curl

```
use linslin\yii2\curl;
$curl = new curl\Curl();

//get http://example.com/
$response = $curl->get('http://example.com/');

if ($curl->errorCode === null) {
   echo $response;
} else {
     // List of curl error codes here https://curl.haxx.se/libcurl/c/libcurl-errors.html
    switch ($curl->errorCode) {
    
        case 6:
            //host unknown example
            break;
    }
} 
```

文档地址：https://github.com/linslin/Yii2-Curl

### 中文转拼音

```
use Overtrue\Pinyin\Pinyin;

// 小内存型
$pinyin = new Pinyin(); // 默认
// 内存型
// $pinyin = new Pinyin('Overtrue\Pinyin\MemoryFileDictLoader');
// I/O型
// $pinyin = new Pinyin('Overtrue\Pinyin\GeneratorFileDictLoader');

$pinyin->convert('带着希望去旅行，比到达终点更美好');
// ["dai", "zhe", "xi", "wang", "qu", "lyu", "xing", "bi", "dao", "da", "zhong", "dian", "geng", "mei", "hao"]

$pinyin->convert('带着希望去旅行，比到达终点更美好', PINYIN_TONE);
// ["dài","zhe","xī","wàng","qù","lǚ","xíng","bǐ","dào","dá","zhōng","diǎn","gèng","měi","hǎo"]

$pinyin->convert('带着希望去旅行，比到达终点更美好', PINYIN_ASCII_TONE);
//["dai4","zhe","xi1","wang4","qu4","lyu3","xing2","bi3","dao4","da2","zhong1","dian3","geng4","mei3","hao3"]
```

更多文档：https://github.com/overtrue/pinyin

### 爬虫

```
use QL\QueryList;

$data = QueryList::get('https://www.baidu.com/s?wd=QueryList')
  // 设置采集规则
  ->rules([ 
      'title'=>array('h3','text'),
      'link'=>array('h3>a','href')
  ])
  ->queryData();

print_r($data);
```

更多文档：http://www.querylist.cc/docs/guide/v4/

### Glide

> Glide是一个用PHP编写的非常简单的按需图像处理库  
> 注意：系统默认只能在storage下使用，已经基础配置完毕，但是系统内未安装，如果需要使用请先安装

```
php composer.phar require --prefer-dist trntv/yii2-glide
```

**用法：**

直接输出一个图像

```
Yii::$app->glide->outputImage('new-upload.jpg', ['w' => 100, 'fit' => 'crop'])
```

创建一个图像

```
Yii::$app->glide->makeImage('new-upload.jpg', ['w' => 100, 'fit' => 'crop'])
```

创建一个带签名才能访问的图像

```
Yii::$app->glide->createSignedUrl(['glide/index', 'path' => 'images/2018-12/27/image_154588883551485657.jpg', 'w' => 175]);
```

> 注意开启设置 `storage/config/main.php` 内的 glide 组件的signKey，否则无效

来源：https://github.com/trntv/yii2-glide  
配套文档：http://glide.thephpleague.com/

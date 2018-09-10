## 组件调用

目录

- 生成二维码
- IP地址转地区

### 生成二维码

```
$qr = Yii::$app->get('qr');

Yii::$app->response->format = Response::FORMAT_RAW;
Yii::$app->response->headers->add('Content-Type', $qr->getContentType());

return $qr
    ->setText('https://2amigos.us')
    ->setLabel('2amigos consulting group llc')
    ->writeString();

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


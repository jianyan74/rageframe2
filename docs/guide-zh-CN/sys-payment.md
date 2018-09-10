## 公用支付

目录

- 支付宝
- 微信
- 银联

### 支付宝

订单

```
// 配置
$config = [
        'notify_url' => 'http://rageframe.com/notify.php',
        'return_url' => 'http://rageframe.com/return.php',
];

// 生成订单
$order = [
    'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
    'total_amount' => 0.01,
    'subject'      => 'test',
];
```

生成参数

```
// 电脑网站支付
$resConfig = Yii::$app->pay->alipay($config)->pc($order);

// app支付
$resConfig = Yii::$app->pay->alipay($config)->app($order);

// 面对面支付
$resConfig = Yii::$app->pay->alipay($config)->f2f($order);

// 手机网站支付
$resConfig = Yii::$app->pay->alipay($config)->wap($order);

```

退款


```
$info = [
      'out_trade_no' => 'The existing Order ID',
      'trade_no' => 'The Transaction ID received in the previous request',
      'refund_amount' => 18.4,
      'out_request_no' => date('YmdHis') . mt_rand(1000, 9999)
];
 
   
Yii::$app->pay->alipay->refund($info); 
   
```


### 微信

订单

```
// 生成订单
$order = [
    'body'         => 'test',
    'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
    'total_fee' => 1,
    'notify_url' => 'http://rageframe.com/notify.php', // 回调地址
    // 'open_id'         => 'okFAZ0-',  //JS支付必填
    // 'auth_code'      => 'ojPztwJ5bRWRt_Ipg',  刷卡支付必填
];
```

生成参数

```
// 原生扫码支付
$resConfig = Yii::$app->pay->wechat->native($order);

// app支付
$resConfig = Yii::$app->pay->wechat->app($order);

// js支付
$resConfig = Yii::$app->pay->wechat->js($order);

// 刷卡支付
$resConfig = Yii::$app->pay->wechat->pos($order);

// H5支付
$resConfig = Yii::$app->pay->wechat->mweb($order);
```

回调


```
$response = Yii::$app->pay->wechat->notify();
```

关闭订单


```
$response = Yii::$app->pay->wechat->close($out_trade_no);
```

查询订单
```
$response = Yii::$app->pay->wechat->query($transaction_id);
```

退款

```
$info = [
    'transaction_id' => $transaction_id, //The wechat trade no
    'out_refund_no'  => $outRefundNo,
    'total_fee'      => 1, //=0.01
    'refund_fee'    => 1, //=0.01
];

$response = Yii::$app->pay->wechat->refund($info);
```

### 银联

```
// TODO
```
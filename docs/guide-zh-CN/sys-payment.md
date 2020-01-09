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
    'notify_url' => 'http://rageframe.com/notify.php', // 支付通知回调地址
    'return_url' => 'http://rageframe.com/return.php', // 买家付款成功跳转地址
    'sandbox' => false, // 沙盒模式
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

// 面对面支付(创建收款二维码)
$resConfig = Yii::$app->pay->alipay($config)->f2f($order);

// 手机网站支付
$resConfig = Yii::$app->pay->alipay($config)->wap($order);
```

扫码收款

```
$request = Yii::$app->pay->alipay->capture();
$request->setBizContent([
    'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999),
    'scene'        => 'bar_code',
    'auth_code'    => '288412621343841260',  //购买者手机上的付款二维码
    'subject'      => 'test',
    'total_amount' => 0.01,
]);

/** @var \Omnipay\Alipay\Responses\AopCompletePurchaseResponse $response */
try {
    $response = $request->send();
    
    if($response->isPaid()){
        /**
         * Payment is successful
         */
    }else{
        /**
         * Payment is not successful
         */
    }
} catch (Exception $e) {
    /**
     * Payment is not successful
     */
}
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

异步/同步通知

```
$request = Yii::$app->pay->alipay([
    'ali_public_key' => '', // 支付宝公钥
])->notify()

try {
    /** @var \Omnipay\Alipay\Responses\AopCompletePurchaseResponse $response */
    $response = $request->send();
    
    if($response->isPaid()){
        /**
         * Payment is successful
         */
        die('success'); //The response should be 'success' only
    } else {
        /**
         * Payment is not successful
         */
        die('fail');
    }
} catch (Exception $e) {
    /**
     * Payment is not successful
     */
    die('fail');
}
```

更多文档：https://github.com/lokielse/omnipay-alipay

### 微信

订单

```
// 生成订单
$order = [
    'body' => 'test', // 内容
    'out_trade_no' => date('YmdHis') . mt_rand(1000, 9999), // 订单号
    'total_fee' => 1,
    'notify_url' => 'http://rageframe.com/notify.php', // 回调地址
    // 'open_id' => 'okFAZ0-',  //JS支付必填
    // 'auth_code' => 'ojPztwJ5bRWRt_Ipg',  刷卡支付必填
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

if ($response->isPaid()) {
    //pay success
    var_dump($response->getRequestData());
}else{
    //pay fail
}

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
    'refund_fee'     => 1, //=0.01
];

$response = Yii::$app->pay->wechat->refund($info);
```

### 银联

订单

```
// 配置
$config = [
    'notify_url' => 'http://rageframe.com/notify.php', // 支付通知回调地址
    'return_url' => 'http://rageframe.com/return.php', // 买家付款成功跳转地址
];

// 生成订单
$order = [
    'orderId'   => date('YmdHis'), //Your order ID
    'txnTime'   => date('YmdHis'), //Should be format 'YmdHis'
    'orderDesc' => 'My order title', //Order Title
    'txnAmt'    => '100', //Order Total Fee
];
```

生成参数

```
// app支付
$resConfig = Yii::$app->pay->union($config)->app($order);

// pc/wap
$resConfig = Yii::$app->pay->union($config)->html($order);
```

回调

```
$response = Yii::$app->pay->union->notify();

if ($response->isPaid()) {
    //pay success
} else {
    //pay fail
}
```

查询订单

```
$response  = Yii::$app->pay->union->query($orderId, $txnTime, $txnAmt);

// 获取 $queryId
$queryId = $response['queryId'];
```

关闭订单

```
$response  = Yii::$app->pay->union->query($orderId, $txnTime, $txnAmt, $queryId);
```

退款

```
$response  = Yii::$app->pay->union->refund($orderId, $txnTime, $txnAmt, $queryId);
```
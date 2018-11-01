## 微信红包

目录

- 发送红包
- 红包预下单接口
- 查询红包信息

引入

与支付接口一样，红包接口也需要配置如下参数，需要特别注意的是，红包相关的全部接口都需要使用 SSL 证书，因此cert_path 以及 cert_key 必须正确

```
$payment = Yii::$app->wechat->payment;
$redpack = $payment->redpack;
```

### 发送红包

> 默认情况下，通过接口发送的红包金额应该在200元以内，但可以通过在调用发送接口时传递场景 ID (scene_id)来发送特定场景的红包，不同场景红包可以由商户自己登录商户平台设置最大金额。scene_id 的可选值及对应含义可参阅微信支付官方文档。

##### 发送普通红包

```
$redpackData = [
    'mch_billno'   => 'xy123456',
    'send_name'    => '测试红包',
    're_openid'    => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
    'total_num'    => 1,  //固定为1，可不传
    'total_amount' => 100,  //单位为分，不小于100
    'wishing'      => '祝福语',
    'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
    'act_name'     => '测试活动',
    'remark'       => '测试备注',
    // ...
];

$result = $redpack->sendNormal($redpackData);
```

##### 发送裂变红包

```
$redpackData = [
    'mch_billno'   => 'xy123456',
    'send_name'    => '测试红包',
    're_openid'    => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
    'total_num'    => 3,  //不小于3
    'total_amount' => 300,  //单位为分，不小于300
    'wishing'      => '祝福语',
    'act_name'     => '测试活动',
    'remark'       => '测试备注',
    'amt_type'     => 'ALL_RAND',  //可不传
    // ...
];

$result = $redpack->sendGroup($redpackData);
```

### 红包预下单接口

红包预下单接口是为摇一摇红包接口配合使用的，在开发摇一摇周边的摇红包相关功能时，需要调用本接口获取红包单号。详情参见[官方文档](http://mp.weixin.qq.com/wiki/7/0ddd50ed2421b99fedd071281c074aab.html#.E7.BA.A2.E5.8C.85.E9.A2.84.E4.B8.8B.E5.8D.95.E6.8E.A5.E5.8F.A3)

```
$redpackData = [
    'hb_type'      => 'NORMAL',  //NORMAL 或 GROUP
    'mch_billno'   => 'xy123456',
    'send_name'    => '测试红包',
    're_openid'    => 'oxTWIuGaIt6gTKsQRLau2M0yL16E',
    'total_num'    => 1,  //普通红包固定为1，裂变红包不小于3
    'total_amount' => 100,  //单位为分，普通红包不小于100，裂变红包不小于300
    'wishing'      => '祝福语',
    'client_ip'    => '192.168.0.1',  //可不传，不传则由 SDK 取当前客户端 IP
    'act_name'     => '测试活动',
    'remark'       => '测试备注',
    'amt_type'     => 'ALL_RAND',
    // ...
];

$result = $redpack->prepare($redpackData);
```

### 查询红包信息

用于商户对已发放的红包进行查询红包的具体信息以及领取情况 ，普通红包和裂变包均使用这一接口进行查询。

```
$mchBillNo = "商户系统内部的订单号（mch_billno）";
$redpack->info($mchBillNo);
```


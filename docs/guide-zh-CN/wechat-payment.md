## 微信支付

目录

- 控制器
- 视图
- 支付结果通知

支付实例化

```
$app = Yii::$app->wechat->payment;
```

### 控制器

> 生成订单

```
$result = $app->order->unify([
    'body' => '腾讯充值中心-QQ会员充值',
    'out_trade_no' => '20150806125346',
    'total_fee' => 88,
    'spbill_create_ip' => '123.12.12.123', // 可选，如不传该参数，SDK 将会自动获取相应 IP 地址
    'notify_url' => 'https://pay.weixin.qq.com/wxpay/pay.action', // 支付结果通知网址，如果不设置则会使用配置里的默认地址
    'trade_type' => 'JSAPI',
    'openid' => 'oUpF8uMuAJO_M2pxb1Q9zNjWeS6o',
]);

if ($result['return_code'] == 'SUCCESS' && $result['result_code'] == 'SUCCESS') {
    $config = $payment->jssdk->sdkConfig($result['prepay_id']);
} else {
    throw new yii\base\ErrorException('微信支付异常, 请稍后再试');
}

return $this->render('wxpay', [
    'jssdk' => $app->jssdk, // $app通过微信的实例来获取
    'config' => $config
]);
```

### 视图

```
<script src="http://res.wx.qq.com/open/js/jweixin-1.2.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    //数组内为jssdk授权可用的方法，按需添加，详细查看微信jssdk的方法
    wx.config(<?php echo $jssdk->buildConfig(array('chooseWXPay'), true) ?>);
    // 发起支付
    wx.chooseWXPay({
        timestamp: <?= $config['timestamp'] ?>,
        nonceStr: '<?= $config['nonceStr'] ?>',
        package: '<?= $config['package'] ?>',
        signType: '<?= $config['signType'] ?>',
        paySign: '<?= $config['paySign'] ?>', // 支付签名
        success: function (res) {
            // 支付成功后的回调函数
        }
    });
</script>
```

### 支付结果通知

> 查看例子，注意关闭csrf，权限验证等，否则微信回调无法正常调用

```
$response = Yii::$app->wechat->payment->handlePaidNotify(function ($message, $fail) {
    // 你的逻辑
    return true;
    // 或者错误消息
    $fail('Order not exists.');
});

$response->send();
```
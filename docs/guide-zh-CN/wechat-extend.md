## 微信扩展

目录

- 设置基础配置信息
- 使用例子
  
> 注意：底层调用了EasyWechat扩展，如果需要更多的扩展使用说明请访问EasyWechat官网

### 设置基础配置信息

> 默认已经动态写入(微信配置、微信支付配置、微信小程序配置)，不用重复配置，backend、frontend、wechat应用已继承该基类，api应用需要自己按需写入。

设置基础配置信息在 `config/params.php`，如果不需要请忽略，默认已配置

```
// 微信配置 具体可参考EasyWechat 
'wechatConfig' => [],
```
[微信配置文档](https://www.easywechat.com/docs/master/official-account/configuration)  
```
// 微信支付配置 具体可参考EasyWechat
'wechatPaymentConfig' => [],
```
[微信支付配置文档](https://www.easywechat.com/docs/master/payment/jssdk)  
```
// 微信小程序配置 具体可参考EasyWechat
'wechatMiniProgramConfig' => [],
```
[微信小程序配置文档](https://www.easywechat.com/docs/master/mini-program/index)  
```
// 微信开放平台第三方平台配置 具体可参考EasyWechat
'wechatOpenPlatformConfig' => [],
```
[微信开放平台第三方平台配置文档](https://www.easywechat.com/docs/master/open-platform/index) 
```
// 微信企业微信配置 具体可参考EasyWechat
'wechatWorkConfig' => [],
```
[企业微信配置文档](https://www.easywechat.com/docs/master/wework/index)

### 使用例子

微信网页授权

```
if (Yii::$app->wechat->isWechat && !Yii::$app->wechat->isAuthorized()) {
    return Yii::$app->wechat->authorizeRequired()->send();
}
```
获取微信SDK实例

```
$app = Yii::$app->wechat->app;
```
获取微信支付SDK实例

```
$payment = Yii::$app->wechat->payment;
```
获取微信小程序实例

```
$miniProgram = Yii::$app->wechat->miniProgram;
```

获取微信开放平台第三方平台实例

```
$openPlatform = Yii::$app->wechat->openPlatform;
```

获取企业微信实例

```
$work = Yii::$app->wechat->work;
```

更多EasyWechat的文档

 [EasyWeChat Docs](https://www.easywechat.com/docs/master).
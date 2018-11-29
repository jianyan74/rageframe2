## 用户信息获取

目录

- 用户信息获取
- 用户信息模拟

### 用户信息获取

```
// 注意这里如果开启模拟微信数据，且未在微信打开这里的数据是模拟的数据
Yii::$app->params['wechatMember'];

// 获取微信当前用户信息，注意不能被模拟
Yii::$app->wechat->user

// 通过openid获取
$user = $app->user->get($openId);
```
**如果不需要自动去获取用户信息怎么办？**

请在自己的控制器创建该属性覆设置为false，代表该控制器进入时候不去获取用户信息

```
/**
 * 默认检测到微信进入自动获取用户信息
 *
 * @var bool
 */
public $openGetWechatUser= false
```

**如果想静默授权获取用户信息怎么办？**

> 注意这里的参数修改适用于其他想修改微信的参数

全局方法

在WController的init方法里面最前方设置

```
Yii::$app->params['wechatConfig']['oauth']['scopes'] = ['snsapi_base'];
```

局部方法

在自己的控制器加入

```
public function init()
{
    Yii::$app->params['wechatConfig']['oauth']['scopes'] = ['snsapi_base'];
    
    parent::init();
}
```

### 用户信息模拟

> 用于方便 PC 开发调试微信  
> 优先级 : 微信用户信息 > 模拟微信用户信息

查看 `wehcat/config/params.php` 的 `simulateUser` 并设置 `switch` 为 `true`

```
    /** ------ 非微信打开的时候是否开启微信模拟数据 ------ **/
    'simulateUser' => [
        'switch' => false,// 微信应用模拟用户检测开关
        'userInfo' => [
            'id' => 'oW6qtS0fitZTWHudEX-7ik',
            'nickname' => '简言',
            'name' => '简言',
            'avatar' => 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM4eoQGHDIsK05kWV5deHKK99ka7d65eecJZ7CRZGTlicuaoH7YzcbzYXo1pDR6N77bdLTwA6F2mZA1cFw7icJxwwSWbVgqk3l6gU/0',
            'original' => [
                'openid' => 'oW6qtS0fitZTWHudEX-7ik',
                'sex' => 1,
                'language' => 'zh_CN',
                'city' => '杭州',
                'province' => '浙江',
                'country' => '中国',
                'headimgurl' => 'http://wx.qlogo.cn/mmopen/Q3auHgzwzM4eoQGHDIsK05kWV5deHKK99ka7d65eecJZ7CRZGTlicuaoH7YzcbzYXo1pDR6N77bdLTwA6F2mZA1cFw7icJxwwSWbVgqk3l6gU/0',
                'privilege' => [],
            ],
            'token' => '10_8ZUhjEP6s_nanE37Z7Zh3kFRA7ZhFRAALBtkCV1WE',
            'provider' => 'WeChat',
        ],
    ],
```
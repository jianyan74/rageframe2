## 公众号绑定

目录

- 绑定
- 微信应用使用注意

> 注意确保自己的微信公众号插件已经安装，如果删除了想重新安装请去 https://github.com/jianyan74/Wechat 下载最新

### 绑定

第一步

> 后台菜单 : 系统管理->网站设置->微信公众号 填入对应的AppId，AppSecret，Token，EncodingAESKey等参数

这里有时候会提示

第二步

> 微信公众号绑定的服务器地址为 当前域名`html5/wechat/receive-msg/index`，完整例子 : `www.rageframe.com/html5/wechat/receive-msg/index`

### 配置信息修改

如果想修改微信用户session保存信息，在 `common\config\main.php` 里面修改

```
/** ------ 微信SDK ------ **/
'wechat' => [
    'class' => 'jianyan\easywechat\Wechat',
    'userOptions' => [],  // 用户身份类参数
    'sessionParam' => 'wechatUser', // 微信用户信息将存储在会话在这个密钥
    'returnUrlParam' => '_wechatReturnUrl', // returnUrl 存储在会话中
],
```

### 微信应用使用注意

如果控制器内需要使用init()方法，必须先继承上级方法parent::init() 不然会导致框架的配置被替换无效
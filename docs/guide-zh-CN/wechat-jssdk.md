## JSSDK

目录

- API
- JSSDK上传的素材下载

### API

- $app->jssdk->buildConfig(array $APIs, $debug = false, $beta = false, $json = true); 获取JSSDK的配置数组，默认返回 JSON 字符串，当 $json 为 false 时返回数组，你可以直接使用到网页中。
- $app->jssdk->setUrl($url) 设置当前URL，如果不想用默认读取的URL，可以使用此方法手动设置，通常不需要。

示例：

我们可以生成js配置文件：

```
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    wx.config(<?php echo $app->jssdk->buildConfig(array('onMenuShareQQ', 'onMenuShareWeibo'), true) ?>);
</script>
```

--------

结果如下：

```
<script src="http://res.wx.qq.com/open/js/jweixin-1.4.0.js" type="text/javascript" charset="utf-8"></script>
<script type="text/javascript" charset="utf-8">
    wx.config({
        debug: true,
        appId: 'wx3cf0f39249eb0e60',
        timestamp: 1430009304,
        nonceStr: 'qey94m021ik',
        signature: '4F76593A4245644FAE4E1BC940F6422A0C3EC03E',
        jsApiList: ['onMenuShareQQ', 'onMenuShareWeibo']
    });
</script>
```

### JSSDK上传的素材下载

```
$stream = Yii::$app->wechat->app->media->get($media_id);
/**
 * $file_path_full 绝对文件目录
 * $file_new_name 文件名
 */
$stream->save($file_path_full, $file_new_name)
```
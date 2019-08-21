## 安全防范

- XSS攻击防范
- CSRF攻击防范
- SQL注入
- Nginx指定目录禁止运行PHP

### XSS攻击

直接显示的数据加上，防止XSS攻击

```
// 页面直接输出
\common\helpers\Html::encode($title) // 纯文本
\common\helpers\HtmlPurifier::process($content) // html显示的文本 HtmlPurifier帮助类的处理过程较为费时，建议增加缓存

// json输出
\yii\helpers\Json::htmlEncode($content);
```
### CSRF攻击防范

如果不用Yii2自带的表单组件可在form表单加入这个开启csrf防范
```
\common\helpers\Html::csrfMetaTags()
```

### SQL注入

请用Yii2自带的AR或者DB操作类来防范SQL注入

### Nginx指定目录禁止运行PHP

这段配置文件一定要放在匹配 `.php` 的规则前面才可以生效，防止上传可执行文件攻击

```
// 单个目录
location ~* ^/attachment/.*\.(php|php5)$ 
{
    deny all;
}

// 多个目录
location ~* ^/(attachment|uploads)/.*\.(php|php5)$ 
{
    deny all; 
}
```

其他

```
# deny accessing php files for the /assets directory
location ~ ^/assets/.*\.php$ {
    deny all;
}
```
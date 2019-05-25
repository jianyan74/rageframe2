<p align="center">
    <h1 align="center">EasyWeChat Composer Plugin</h1>
</p>

<p align="center">
    <a href="https://travis-ci.org/mingyoung/easywechat-composer"><img src="https://travis-ci.org/mingyoung/easywechat-composer.svg" alt="Build Status"></a>
    <a href="https://scrutinizer-ci.com/g/mingyoung/easywechat-composer/?branch=master"><img src="https://scrutinizer-ci.com/g/mingyoung/easywechat-composer/badges/quality-score.png?b=master" alt="Scrutinizer Code Quality"></a>
    <a href="https://packagist.org/packages/easywechat-composer/easywechat-composer"><img src="https://poser.pugx.org/easywechat-composer/easywechat-composer/v/stable.svg" alt="Latest Stable Version"></a>
    <a href="https://packagist.org/packages/easywechat-composer/easywechat-composer"><img src="https://poser.pugx.org/easywechat-composer/easywechat-composer/d/total.svg" alt="Total Downloads"></a>
    <a href="https://packagist.org/packages/easywechat-composer/easywechat-composer"><img src="https://poser.pugx.org/easywechat-composer/easywechat-composer/license.svg" alt="License"></a>
</p>

Usage
---

Set the `type` to be `easywechat-extension` in your package composer.json file:

```json
{
    "name": "your/package",
    "type": "easywechat-extension"
}
```

Specify server observer classes in the extra section:

```json
{
    "name": "your/package",
    "type": "easywechat-extension",
    "extra": {
        "observers": [
            "Acme\\Observers\\Handler"
        ]
    }
}
```

Examples
---
* [easywechat-composer/open-platform-testcase](https://github.com/mingyoung/open-platform-testcase)

Server Delegation
---

> 目前仅支持 Laravel

1. 在 `config/app.php` 中添加 `EasyWeChatComposer\Laravel\ServiceProvider::class`

2. 在**本地项目**的 `.env` 文件中添加如下配置：

```
EASYWECHAT_DELEGATION=true # false 则不启用
EASYWECHAT_DELEGATION_HOST=https://example.com # 线上域名
```

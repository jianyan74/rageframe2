## Access Token

目录

- 获取 access token 实例

### 获取 access token 实例

```
$accessToken = $app->access_token;
$token = $accessToken->getToken(); // token 数组  token['access_token'] 字符串
$token = $accessToken->getToken(true); // 强制重新从微信服务器获取 token.
```
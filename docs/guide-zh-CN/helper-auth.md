## 权限辅助类

目录

- 校验权限是否拥有
- 批量校验权限是否拥有

> 未登录默认不校验权限，注意需要判断权限的地方先引入登录认证

引入

```
use common\helpers\Auth;
```

### 校验权限是否拥有

```
/**
 * 校验权限是否拥有
 *
 * @param string $route
 * @return bool
 */
Auth::verify($route);
```

### 批量校验权限是否拥有

```
/**
 * 过滤自己拥有的权限
 * 传递权限数组返回自己拥有的权限数组
 * 
 * @param array $route
 * @return array|bool
 */
Auth::verifyBatch($route);
```
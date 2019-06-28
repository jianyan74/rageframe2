## 权限辅助类

目录

- 校验权限是否拥有
- 批量校验权限是否拥有

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
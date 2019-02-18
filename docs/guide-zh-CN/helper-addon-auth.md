## 模块权限辅助类

目录

- 校验权限是否拥有
- 批量校验权限是否拥有

> 注意：只能在模块下使用，其他地方使用会报错

引入

```
use common\helpers\AddonAuthHelper;
```

### 校验权限是否拥有

```
/**
 * 校验权限是否拥有
 *
 * @param string $route
 * @return bool
 */
AddonAuthHelper::verify($route);
```

### 批量校验权限是否拥有

```
/**
 * 过滤自己拥有的权限
 * 传递权限数组返回自己拥有的权限
 
 * @param array $route
 * @return array|bool
 */
AddonAuthHelper::verifyBatch($route);
```
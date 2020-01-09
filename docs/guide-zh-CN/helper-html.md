## Html辅助类

目录

- 新增按钮
- 编辑按钮
- 删除按钮
- 普通按钮
- 状态标签
- 是否标签

> 关于链接按钮的用法和Yii2自带的Html辅助类一样，只不过系统在其上面封装了一层

引入

```
use common\helpers\Html;
```

### 新增按钮

```
Html::create(['index']);
```

### 编辑按钮

```
Html::edit(['edit', 'id' => 1]);
```

### 删除按钮

```
Html::create(['delete', 'id' => 1]);
```

### 普通按钮

```
Html::linkButton(['test', 'id' => 1], '测试');
```

### 状态标签

```
// 0：显示;1：隐藏
Html::status(1);
```

### 是否标签

```
// 1：是;0：否
Html::whether(1);
```
   
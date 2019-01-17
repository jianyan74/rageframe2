## Html辅助类

目录

- 新增按钮
- 编辑按钮
- 删除按钮
- 普通按钮
- 状态标签
- 是否标签
- 点击大图
- 头像图片判断

> 关于链接按钮的用法和Yii2自带的Html辅助类一样，只不过系统在其上面封装了一层

引入

```
use common\helpers\HtmlHelper;
```

### 新增按钮

```
HtmlHelper::create(['index']);
```

### 编辑按钮

```
HtmlHelper::edit(['edit', 'id' => 1]);
```

### 删除按钮

```
HtmlHelper::create(['delete', 'id' => 1]);
```

### 普通按钮

```
HtmlHelper::linkButton(['test', 'id' => 1], '测试');
```

### 状态标签

```
// 0：显示;1：隐藏
HtmlHelper::status(1);
```

### 是否标签

```
// 1：是;0：否
HtmlHelper::whether(1);
```

### 点击大图

```
/**
 * 点击大图
 *
 * @param string $imgSrc
 * @param int $width 宽度 默认45px
 * @param int $height 高度 默认45px
 */
HtmlHelper::imageFancyBox($imgSrc, $width = 45, $height = 45)
```

### 头像图片判断

```
/**
 * 头像
 * 注意：如果没有头像会默认显示系统内的一张图片
 * 
 * @param string $head_portrait
 * @return mixed
 */
HtmlHelper::headPortrait($head_portrait)
```
   
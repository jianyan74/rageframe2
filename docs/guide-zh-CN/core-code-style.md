Yii 2 核心框架代码风格
===============================

下面的代码样式用于Yii 2.x 核心和官方扩展开发.如果您想将请求代码拉入内核，请考虑使用它. 我们并不强迫您在应用程序中使用这种代码样式.你可以自由选择更适合你的.

你可以在这里获得CodeSniffer的配置: https://github.com/yiisoft/yii2-coding-standards

## 1. 概述

总体上我们使用 [PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md)
兼容的风格，所以一切适用
[PSR-2](https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-2-coding-style-guide.md) 也适用于我们的代码样式.

- 文件必须使用 `<?php` 或 `<?=` 标签.
- 文件末尾应该有一个换行符.
- 对于PHP代码，文件只能使用UTF-8，不能使用BOM.
- 代码必须使用4个空格缩进, 而不是 tabs.
- 类名必须在 `StudlyCaps`.
- 类常量必须在所有带有下划线分隔符的大写字母中声明.
- 方法名必须在 `camelCase`.
- 属性名必须在 `camelCase`.
- 如果属性名是私有的，则它们必须以初始下划线开头.
- 总是使用 `elseif` 而不是 `else if`.

## 2. 文件

### 2.1. PHP 标签

- PHP 代码 必须 使用 `<?php ?>` 或 `<?=` 标签; 它不能使用其他标记变体，例如 `<?`.
- 如果文件只包含PHP，它不应该有尾随的 `?>`.
- 不要在行尾添加尾随空格.
- 任何包含PHP代码的文件都应该以扩展名 `.php` 结束.

### 2.2. 字符编码

PHP 代码只能使用 UTF-8 没有 BOM 头.

## 3. 类名

类名 必须 在 `StudlyCaps`. 例如, `Controller`, `Model`.

## 4. Classes

The term "class" refers to all classes and interfaces here.

- 类名应该使用 `CamelCase`.
- 大括号应该总是写在类名下面的那行上.
- 每个类都必须有一个符合PHPDoc的文档块.
- 类中的所有代码都必须缩进4个空格.
- 一个PHP文件中应该只有一个类.
- 所有类都应该有命名空间.
- 类名应该与文件名匹配. 类命名空间应该匹配目录结构.

```php
/**
 * 实例
 */
class MyClass extends \yii\base\BaseObject implements MyInterface
{
    // 代码
}
```

### 4.1. 常量

类常量必须在所有带有下划线分隔符的大写字母中声明.
例如:

```php
<?php
class Foo
{
    const VERSION = '1.0';
    const DATE_APPROVED = '2012-06-01';
}
```
### 4.2. Properties

- When declaring public class members specify `public` keyword explicitly.
- Public and protected variables should be declared at the top of the class before any method declarations.
  Private variables should also be declared at the top of the class but may be added right before the methods
  that are dealing with them in cases where they are only related to a small subset of the class methods.
- The order of property declaration in a class should be ascending based on their visibility: from public over protected to private.
- There are no strict rules for ordering properties that have the same visibility.
- For better readability there should be no blank lines between property declarations and two blank lines
  between property and method declaration sections. One blank line should be added between the different visibility groups.
- Private 变量应该像这样命名 `$_varName`.
- Public 类成员和独立变量应该使用 `$camelCase`
  第一个字母小写.
- Use descriptive names. Variables such as `$i` and `$j` are better not to be used.

例如:

```php
<?php
class Foo
{
    public $publicProp1;
    public $publicProp2;

    protected $protectedProp;

    private $_privateProp;


    public function someMethod()
    {
        // ...
    }
}
```

### 4.3. 方法

- 函数和方法应该使用 `camelCase` 这样子的第一个字母小写.
- 名称本身应该是描述性的，指示函数的用途.
- 类方法应该始终使用声明可见性 `private`, `protected` 和
  `public` modifiers. `var` 是不允许的.
- 函数的左大括号应该位于函数声明之后的那行上.

```php
/**
 * Documentation
 */
class Foo
{
    /**
     * Documentation
     */
    public function bar()
    {
        // code
        return $value;
    }
}
```

### 4.4 PHPDoc 块

 - `@param`, `@var`, `@property` and `@return` must declare types as `bool`, `int`, `string`, `array` or `null`.
   You can use a class names as well such as `Model` or `ActiveRecord`.
 - For a typed arrays use `ClassName[]`.
 - The first line of the PHPDoc must describe the purpose of the method.
 - If method checks something (`isActive`, `hasClass`, etc) the first line should start with `Checks whether`.
 - `@return` should explicitly describe what exactly will be returned.

```php
/**
 * 检查IP是否在子网范围内
 *
 * @param string $ip 一个IPv4或IPv6地址
 * @param int $cidr the CIDR lendth
 * @param string $range subnet in CIDR format e.g. `10.0.0.0/8` or `2001:af::/64`
 * @return bool IP是否在子网范围内
 */
 private function inRange($ip, $cidr, $range)
 {
   // ...
 }
```

### 4.5 构造方法

- `__construct` 应该使用PHP 4样式的构造函数.

## 5 PHP

### 5.1 Types

- All PHP types and values should be used lowercase. That includes `true`, `false`, `null` and `array`.

Changing type of an existing variable is considered as a bad practice. Try not to write such code unless it is really necessary.


```php
public function save(Transaction $transaction, $argument2 = 100)
{
    $transaction = new Connection; // bad
    $argument2 = 200; // good
}
```

### 5.2 字符串

- 如果字符串不包含变量或单引号, 使用单引号.

```php
$str = 'Like this.';
```

- If string contains single quotes you can use double quotes to avoid extra escaping.

#### 变量替换

```php
$str1 = "Hello $username!";
$str2 = "Hello {$username}!";
```

以下内容是不允许的:

```php
$str3 = "Hello ${username}!";
```

#### 连接

连接字符串时，在点周围添加空格:

```php
$name = 'Yii' . ' Framework';
```

当字符串很长时，格式如下:

```php
$sql = "SELECT *"
    . "FROM `post` "
    . "WHERE `id` = 121 ";
```

### 5.3 数组

对于数组，我们使用PHP 5.4短数组语法.

#### Numerically indexed

- 不要使用负数作为数组索引.

在声明数组时使用以下格式:

```php
$arr = [3, 14, 15, 'Yii', 'Framework'];
```

如果一行有太多的元素:

```php
$arr = [
    3, 14, 15,
    92, 6, $test,
    'Yii', 'Framework',
];
```

#### 关联数组

对关联数组使用以下格式:

```php
$config = [
    'name' => 'Yii',
    'options' => ['usePHP' => true],
];
```

### 5.4 control statements

- 控件语句条件必须在圆括号前后各有一个空格.
- 括号内的运算符应该用空格分隔.
- 左大括号在同一条直线上.
- 右大括号在新行上.
- 始终对单行语句使用大括号.

```php
if ($event === null) {
    return new Event();
}
if ($event instanceof CoolEvent) {
    return $event->instance();
}
return null;


// 以下内容是不允许的:
if (!$model && null === $event)
    throw new Exception('test');
```

Prefer avoiding `else` after `return` where it makes sense.
Use [guard conditions](http://refactoring.com/catalog/replaceNestedConditionalWithGuardClauses.html).

```php
$result = $this->getResult();
if (empty($result)) {
    return true;
} else {
    // process result
}
```

更好的

```php
$result = $this->getResult();
if (empty($result)) {
   return true;
}

// process result
```

#### switch

为switch使用以下格式:

```php
switch ($this->phpType) {
    case 'string':
        $a = (string) $value;
        break;
    case 'integer':
    case 'int':
        $a = (int) $value;
        break;
    case 'boolean':
        $a = (bool) $value;
        break;
    default:
        $a = null;
}
```

### 5.5 函数调用

```php
doIt(2, 3);

doIt(['a' => 'b']);

doIt('a', [
    'a' => 'b',
    'c' => 'd',
]);
```

### 5.6 匿名函数(lambda)声明

Note space between `function`/`use` tokens and open parenthesis:

```php
// good
$n = 100;
$sum = array_reduce($numbers, function ($r, $x) use ($n) {
    $this->doMagic();
    $r += $x * $n;
    return $r;
});

// bad
$n = 100;
$mul = array_reduce($numbers, function($r, $x) use($n) {
    $this->doMagic();
    $r *= $x * $n;
    return $r;
});
```

文档
-------------

- Refer to [phpDoc](http://phpdoc.org/) for documentation syntax.
- 没有文档的代码是不允许的.
- All class files must contain a "file-level" docblock at the top of each file
  and a "class-level" docblock immediately above each class.
- There is no need to use `@return` if method does return nothing.
- All virtual properties in classes that extend from `yii\base\BaseObject`
  are documented with an `@property` tag in the class doc block.
  These annotations are automatically generated from the `@return` or `@param`
  tag in the corresponding getter or setter by running `./build php-doc` in the build directory.
  You may add an `@property` tag
  to the getter or setter to explicitly give a documentation message for the property
  introduced by these methods when description differs from what is stated
  in `@return`. Here is an example:

  ```php
    <?php
    /**
     * Returns the errors for all attribute or a single attribute.
     * @param string $attribute attribute name. Use null to retrieve errors for all attributes.
     * @property array An array of errors for all attributes. Empty array is returned if no error.
     * The result is a two-dimensional array. See [[getErrors()]] for detailed description.
     * @return array errors for all attributes or the specified attribute. Empty array is returned if no error.
     * Note that when returning errors for all attributes, the result is a two-dimensional array, like the following:
     * ...
     */
    public function getErrors($attribute = null)
  ```

#### 文件

```php
<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */
```

#### 类

```php
/**
 * Component is the base class that provides the *property*, *event* and *behavior* features.
 *
 * @include @yii/docs/base-Component.md
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class Component extends \yii\base\BaseObject
```


#### 函数 / 方法

```php
/**
 * 返回事件的附加事件处理程序列表.
 * You may manipulate the returned [[Vector]] object by adding or removing handlers.
 * 例如,
 *
 * ```
 * $component->getEventHandlers($eventName)->insertAt(0, $eventHandler);
 * ```
 *
 * @param string $name 事件名称
 * @return 事件的附加事件处理程序的向量列表
 * @throws 如果没有定义事件的报错为Exception
 */
public function getEventHandlers($name)
{
    if (!isset($this->_e[$name])) {
        $this->_e[$name] = new Vector;
    }
    $this->ensureBehaviors();
    return $this->_e[$name];
}
```

#### Markdown

As you can see in the examples above we use markdown to format the phpDoc comments.

There is additional syntax for cross linking between classes, methods and properties in the documentation:

- `[[canSetProperty]]` will create a link to the `canSetProperty` method or property of the same class.
- `[[Component::canSetProperty]]` will create a link to `canSetProperty` method of the class `Component` in the same namespace.
- `[[yii\base\Component::canSetProperty]]` will create a link to `canSetProperty` method of the class `Component` in namespace `yii\base`.
- `[[Component]]` will create a link to the `Component` class in the same namespace. Adding namespace to the class name is also possible here.

To give one of the above mentioned links another label than the class or method name you can use the syntax shown in the following example:

```
... as displayed in the [[header|header cell]].
```

The part before the | is the method, property or class reference while the part after | is the link label.

It is also possible to link to the Guide using the following syntax:

```markdown
[link to guide](guide:file-name.md)
[link to guide](guide:file-name.md#subsection)
```


#### Comments

- 一行注释应该以 `//` 开始，并不是 `#`.
- 一行注释应该在它自己的行上.

附加规则
----------------

### `=== []` vs `empty()`

尽可能使用 `empty()`.

### 多个返回点

当嵌套条件开始变得混乱时，尽早返回. 如果方法很短，也没有关系.

### `self` vs. `static`

除下列情况外，请始终使用`static`

- 访问常量必须通过 `self`: `self::MY_CONSTANT`
- 访问私有静态属性必须通过 `self`: `self::$_events`
- 它允许对方法调用使用 `self`，比如对当前实现的递归调用，而不是扩展类实现.

### value for "don't do something"

Properties allowing to configure component not to do something should accept value of `false`. `null`, `''`, or `[]` should not be assumed as such.

### 目录/命名空间名称

- 使用小写
- 表示物体的名词用复数形式 (e.g. validators)
- 用单数形式表示相关的名称 functionality/features (e.g. web)
- 更喜欢单字名称空间
- 如果单个单词不合适，使用camelCase


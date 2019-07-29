## 服务层Server

- 配置
- 调用

### 配置

如果需要新服务层调用方法 请在 `services\Application` 中的 `childService` 添加，例如：

```
/**
 * @var array
 */
public $childService = [
    'example' => [
        'class' => 'services\example\ExampleService',
        // 子服务
        'childService' => [
            'rule' => [
                'class' => 'services\example\rule\RuleService',
            ],
        ],
    ],
];
```

以上例子代表了添加了一个 example 服务及 example 的子服务 rule

### 调用

```
// 调用 example 服务里面的 index() 方法
$service = Yii::$app->services->example->index();

// 调用 example 的子服务 rule 里面的 index() 方法
$childService = Yii::$app->services->example->rule->index();
```

扩展说明：[跳转地址](http://www.fancyecommerce.com/2016/07/27/yii2-%e7%bb%99yii-%e6%b7%bb%e5%8a%a0%e4%b8%80%e4%b8%aa%e5%8f%98%e9%87%8f%ef%bc%8c%e5%b9%b6%e5%83%8f%e7%bb%84%e4%bb%b6component%e9%82%a3%e6%a0%b7%e5%8f%af%e4%bb%a5%e6%b7%bb%e5%8a%a0%e5%8d%95%e4%be%8b/)
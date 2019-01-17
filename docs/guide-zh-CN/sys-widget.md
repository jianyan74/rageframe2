## 表单控件

目录

- 颜色选择器
- 日期控件
- 时间控件
- 日期时间控件
- 日期区间控件
- 图片上传控件
- 多图上传控件
- 文件上传控件
- 多文件上传控件
- 多Input框控件
- Select2
- 省市区控件
- 百度编辑器

### 颜色选择器

```
use kartik\color\ColorInput;
```
```
<?= $form->field($model, 'color')->widget(ColorInput::classname(), [
    'options' => ['placeholder' => '请选择颜色'],
]);?>
```

### 日期控件

```
<?= $form->field($model,'test')->widget('kartik\date\DatePicker',[
    'language'  => 'zh-CN',
    'layout'=>'{picker}{input}',
    'pluginOptions' => [
        'format'         => 'yyyy-mm-dd',
        'todayHighlight' => true,//今日高亮
        'autoclose'      => true,//选择后自动关闭
        'todayBtn'       => true,//今日按钮显示
    ],
    'options'=>[
        'class'     => 'form-control no_bor',
        'readonly'  => 'readonly',//禁止输入
    ]
]);?>
```

### 时间控件

```
<?= $form->field($model,'test')->widget('kartik\time\TimePicker',[
          'language' => 'zh-CN',
          'pluginOptions' => [
                 'showSeconds' => true
            ]
]);?>
```

### 日期时间控件

```
use kartik\datetime\DateTimePicker;
```
```
<?= $form->field($model, 'test')->widget(DateTimePicker::className(), [
        'language' => 'zh-CN',
        'options' => [
            'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',$model->start_time),
        ],
        'pluginOptions' => [
            'format' => 'yyyy-mm-dd hh:ii',
            'todayHighlight' => true,//今日高亮
            'autoclose' => true,//选择后自动关闭
            'todayBtn' => true,//今日按钮显示
        ]
]);?>
```

### 日期区间控件

```
use kartik\daterange\DateRangePicker;
```

```
$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;
```

```
<?= DateRangePicker::widget([
    'name' => 'queryDate',
    'value' => date('Y-m-d') . '-' . date('Y-m-d'),
    'readonly' => 'readonly',
    'useWithAddon' => true,
    'convertFormat' => true,
    'startAttribute' => 'from_date',
    'endAttribute' => 'to_date',
    'startInputOptions' => ['value' => date('Y-m-d')],
    'endInputOptions' => ['value' => date('Y-m-d')],
    'pluginOptions' => [
        'locale' => ['format' => 'Y-m-d'],
    ]
]) . $addon;?>
```

具体参考：http://demos.krajee.com/date-range

### 图片上传控件

> 注意OSS/七牛暂不支持切片和缩略图操作

```
<?= $form->field($model, 'cover')->widget('common\widgets\webuploader\Images', [
     'config' => [
          // 可设置自己的上传地址, 不设置则默认地址
          // 'server' => '',
         'pick' => [
             'multiple' => false,
         ],
        'formData' => [
            // 不配置则不生成缩略图
            'thumb' => [
                [
                    'widget' => 100,
                    'height' => 100,
                ],
                [
                    'widget' => 200,
                    'height' => 200,
                ],
            ],
            'drive' => 'local',// 默认本地 qiniu/oss 上传
        ],
         'chunked' => false,// 开启分片上传
         'chunkSize' => 512 * 1024,// 分片大小
         'independentUrl' => false, // 独立上传地址, 如果设置了true则不受全局上传地址控制 
     ]
]);?>
```
获取缩略图路径查看 [字符串辅助类](helper-string.md) 的 `获取缩略图地址`  
config 更多参考 http://fex.baidu.com/webuploader/doc/

### 多图上传控件

> 注意传入的value值为数组,例如: array('img1.jpg', 'img2.jpg')

```
<?= $form->field($model, 'covers')->widget('common\widgets\webuploader\Images', [
     'config' => [ // 配置同图片上传
         // 'server' => '',
         'pick' => [
             'multiple' => ture,
         ],
         'formData' => [
             // 不配置则不生成缩略图
             // 'thumb' => [
             //     [
             //         'widget' => 100,
             //         'height' => 100,
             //     ],
             // ]
         ],
     ]
]);?>

config 更多参考 http://fex.baidu.com/webuploader/doc/
```

### 文件上传控件

> 注意文件上传不支持缩略图配置

```
<?= $form->field($model, 'file')->widget('common\widgets\webuploader\Files', [
     'config' => [ // 配置同图片上传
         // 'server' => \yii\helpers\Url::to(['file/files']), // 默认files 支持videos/voices/images方法验证
         'pick' => [
             'multiple' => false,
         ]
     ]
]);?>
```

### 多文件上传控件

> 注意多文件上传不支持缩略图配置  
> 注意传入的value值为数组,例如: array('img1.jpg', 'img2.jpg')

```
<?= $form->field($model, 'files')->widget('common\widgets\webuploader\Files', [
     'config' => [ // 配置同图片上传
          // 'server' => '',
         'pick' => [
             'multiple' => ture,
         ]
     ]
]);?>
```

### 多Input框控件

```
use unclead\multipleinput\MultipleInput;

...

<?= $form->field($model, 'schedule')->widget(MultipleInput::className(), [
    'max' => 4,
    'columns' => [
        [
            'name'  => 'user_id',
            'type'  => 'dropDownList',
            'title' => 'User',
            'defaultValue' => 1,
            'items' => [
                1 => 'User 1',
                2 => 'User 2'
            ]
        ],
        [
            'name'  => 'day',
            'type'  => \kartik\date\DatePicker::className(),
            'title' => 'Day',
            'value' => function($data) {
                return $data['day'];
            },
            'items' => [
                '0' => 'Saturday',
                '1' => 'Monday'
            ],
            'options' => [
                'pluginOptions' => [
                    'format' => 'dd.mm.yyyy',
                    'todayHighlight' => true
                ]
            ]
        ],
        [
            'name'  => 'priority',
            'title' => 'Priority',
            'enableError' => true,
            'options' => [
                'class' => 'input-priority'
            ]
        ]
    ]
 ]);
?>
```
更多参考：https://github.com/unclead/yii2-multiple-input

### Select2

```
use kartik\select2\Select2

// Usage with ActiveForm and model
echo $form->field($model, 'state_1')->widget(Select2::classname(), [
    'data' => $data,
    'options' => ['placeholder' => 'Select a state ...'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);
```

更多参考：http://demos.krajee.com/widget-details/select2

### 省市区控件

```
<?= \backend\widgets\provinces\Provinces::widget([
    'form' => $form,
    'model' => $model,
    'provincesName' => 'provinces',// 省字段名
    'cityName' => 'city',// 市字段名
    'areaName' => 'area',// 区字段名
    // 'template' => 'short' //合并为一行显示
]); ?>
```

### 百度编辑器

视图

```
<?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::className()) ?>

// 自定义配置参数用法
<?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::className(), [
     'config' => [

      ],
    'formData' => [
        'drive' => 'local', // 默认本地 支持qiniu/oss 上传
        'thumb' => [ // 图片缩略图
            [
                'widget' => 100,
                'height' => 100,
            ],
        ]
    ],
]) ?>
```

更多文档：http://fex.baidu.com/ueditor/#start-start


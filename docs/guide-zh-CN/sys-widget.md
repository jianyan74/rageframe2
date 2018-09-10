## 表单控件

目录

- 颜色选择器
- 日期控件
- 时间控件
- 日期时间控件
- 日期范围选择控件
- 日期区间控件
- 图片上传控件
- 多图上传控件
- 文件上传控件
- 多文件上传控件
- 多Input框控件
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
use dosamigos\datetimepicker\DateTimePicker;
```
```
<?= $form->field($model, 'test')->widget(DateTimePicker::className(), [
    'language' => 'zh-CN',
    'template' => '{button}{reset}{input}',
    'options' => [
        'value' => $model->isNewRecord ? '' : date('Y-m-d H:i:s',$model->test),
    ],
    'clientOptions' => [
        'format' => 'yyyy-mm-dd hh:ii:ss',
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
<?= $form->field($model, 'date_range', [
        'addon'=>['prepend'=>['content'=>'<i class="glyphicon glyphicon-calendar"></i>']],
        'options'=>['class'=>'drp-container form-group']
    ])->widget(DateRangePicker::classname(), [
        'useWithAddon'=>true
    ]);?>
```

具体参考：http://demos.krajee.com/date-range

### 图片上传控件

```
<?= $form->field($model, 'cover')->widget('common\widgets\webuploader\Images', [
     'config' => [
          // 可设置自己的上传地址, 不设置则默认地址
          // 'server' => Url::to(['/file/qiniu']),//七牛上传 (二选一)
          // 'server' => Url::to(['/file/ali-oss']),//阿里云Oss上传
         'pick' => [
             'multiple' => false,
         ],
         // 不配置则不生成缩略图
         'formData' => [
             // 'thumbWidget' => 100, // 缩略图宽度 px
             // 'thumbHeight' => 100, // 缩略图高度 px
         ], 
         'chunked' => false,// 开启分片上传
         'chunkSize' => 512 * 1024,// 分片大小
     ]
]);?>

config 更多参考 http://fex.baidu.com/webuploader/doc/
```

### 多图上传控件

注意传入的value值为数组

```
<?= $form->field($model, 'covers')->widget('common\widgets\webuploader\Images', [
     'config' => [ // 配置同图片上传
         'pick' => [
             'multiple' => ture,
         ]
     ]
]);?>

config 更多参考 http://fex.baidu.com/webuploader/doc/
```

### 文件上传控件

```
<?= $form->field($model, 'file')->widget('common\widgets\webuploader\Files', [
     'config' => [ // 配置同图片上传
         'pick' => [
             'multiple' => false,
         ]
     ]
]);?>
```

### 多文件上传控件

```
<?= $form->field($model, 'files')->widget('common\widgets\webuploader\Files', [
     'config' => [ // 配置同图片上传
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
```
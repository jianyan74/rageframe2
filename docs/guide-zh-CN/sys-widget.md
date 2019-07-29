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
- 图片裁剪上传
- 多Input框控件
- 地图经纬度选择
- Select2
- 省市区控件
- 省市区控件(复选框)
- 百度编辑器
- TreeGrid

### 颜色选择器

```
<?= $form->field($model, 'color')->widget(kartik\color\ColorInput::class, [
    'options' => ['placeholder' => '请选择颜色'],
]);?>
```

### 日期控件

```
<?= $form->field($model, 'date')->widget(kartik\date\DatePicker::class, [
    'language' => 'zh-CN',
    'layout'=>'{picker}{input}',
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd',
        'todayHighlight' => true,//今日高亮
        'autoclose' => true,//选择后自动关闭
        'todayBtn' => true,//今日按钮显示
    ],
    'options'=>[
        'class' => 'form-control no_bor',
    ]
]);?>
```

### 时间控件

```
<?= $form->field($model, 'time')->widget(kartik\time\TimePicker::class, [
    'language' => 'zh-CN',
    'pluginOptions' => [
        'showSeconds' => true
    ]
]);?>
```

### 日期时间控件

```
<?= $form->field($model, 'start_time')->widget(kartik\datetime\DateTimePicker::class, [
    'language' => 'zh-CN',
    'options' => [
        'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',$model->start_time),
    ],
    'pluginOptions' => [
        'format' => 'yyyy-mm-dd hh:ii',
        'todayHighlight' => true, // 今日高亮
        'autoclose' => true, // 选择后自动关闭
        'todayBtn' => true, // 今日按钮显示
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

> 注意OSS/七牛暂不支持切片和缩略图操作，以下是完整案例

```
<?= $form->field($model, 'cover')->widget('common\widgets\webuploader\Files', [
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
                         'width' => 100,
                         'height' => 100,
                     ],
                     [
                         'width' => 200,
                         'height' => 200,
                     ],
                 ],
                 'drive' => 'local',// 默认本地 支持 qiniu/oss/cos 上传
             ],
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
<?= $form->field($model, 'covers')->widget('common\widgets\webuploader\Files', [
        'config' => [ // 配置同图片上传
             // 'server' => '',
             'pick' => [
                 'multiple' => true,
             ],
             'formData' => [
                 // 不配置则不生成缩略图
                 // 'thumb' => [
                 //     [
                 //         'width' => 100,
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
     'type' => 'files',
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
     'type' => 'files',
     'config' => [ // 配置同图片上传
          // 'server' => '',
         'pick' => [
             'multiple' => true,
         ]
     ]
]);?>
```

### 图片裁剪上传

```
<?= $form->field($model, 'head_portrait')->widget(\backend\widgets\cropper\Cropper::class, [
        'config' => [
              // 可设置自己的上传地址, 不设置则默认地址
              // 'server' => '',
         ],
        'formData' => [
            'drive' => 'local',// 默认本地 支持 qiniu/oss/cos 上传
        ],
]); ?>
```

### 多Input框控件

```
<?= $form->field($model, 'schedule')->widget(unclead\multipleinput\MultipleInput::class, [
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
            'type'  => \kartik\date\DatePicker::class,
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

### 地图经纬度选择

```
// 注意提前申请好对应地图的key
<?= $form->field($model, 'address')->widget(\backend\widgets\selectmap\Map::class, [
    'type' => 'amap', // amap高德;tencent:腾讯;baidu:百度
]); ?>
```

### Select2

```
<?= $form->field($model, 'tag')->widget(kartik\select2\Select2::class, [
    'data' => [
        1 => "First", 2 => "Second", 3 => "Third",
        4 => "Fourth", 5 => "Fifth"
    ],
    'options' => ['placeholder' => '请选择'],
    'pluginOptions' => [
        'allowClear' => true
    ],
]);?>
```

更多参考：http://demos.krajee.com/widget-details/select2

### 省市区控件

```
<?= \backend\widgets\provinces\Provinces::widget([
    'form' => $form,
    'model' => $model,
    'provincesName' => 'province_id',// 省字段名
    'cityName' => 'city_id',// 市字段名
    'areaName' => 'area_id',// 区字段名
    // 'template' => 'short' //合并为一行显示
]); ?>
```

### 省市区控件(复选框)

> 注意：目前一个页面仅支持调用一次本控件

触发按钮

```
 <a class="js-select-city btn btn-primary btn-sm" data-toggle="modal" data-target="#ajaxModalLgForExpress">指定地区城市</a>
```

会把选择的省中文显示在此处(可选)

```
<span class="js-region-info region-info"></span>
```

调用

```
<?= \backend\widgets\area\Area::widget([
    'form' => $form,
    'model' => $model,
    'provincesName' => 'province_ids',// 省字段名
    'cityName' => 'city_ids',// 市字段名
    'areaName' => 'area_ids',// 区字段名
    'notChooseProvinceIds' => [], // 不可选省id数组
    'notChooseCityIds' => [], // 不可选市id数组
    'notChooseAreaIds' => [], // 不可选区id数组
    'level' => 3 // 可以只选省/省市/省市区对应为1/2/3
]); ?>
```

### 百度编辑器

视图

```
<?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::class) ?>

// 自定义配置参数用法
<?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::class, [
     'config' => [

      ],
    'formData' => [
        'drive' => 'local', // 默认本地 支持qiniu/oss/cos 上传
        'thumb' => [ // 图片缩略图
            [
                'width' => 100,
                'height' => 100,
            ],
        ]
    ],
]) ?>
```

更多文档：http://fex.baidu.com/ueditor/#start-start

### TreeGrid

控制器

```
use yii\web\Controller;
use Yii;
use yii\data\ActiveDataProvider;

class TreeController extends Controller
{

    /**
     * Lists all Tree models.
     * @return mixed
     */
    public function actionIndex()
    {
        $query = Tree::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'pagination' => false
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider
        ]);
    }
```

视图


```
use leandrogehlen\treegrid\TreeGrid;
  
<?= TreeGrid::widget([
    'dataProvider' => $dataProvider,
    'keyColumnName' => 'id',
    'parentColumnName' => 'pid',
    'parentRootValue' => '0', //first parentId value
    'pluginOptions' => [
        'initialState' => 'collapsed',
    ],
    'options' => ['class' => 'table table-hover'],
    'columns' => [
        [
            'attribute' => 'title',
            'format' => 'raw',
            'value' => function ($model, $key, $index, $column){
                return $model->title . Html::a(' <i class="icon ion-android-add-circle"></i>', ['ajax-edit', 'pid' => $model['id']], [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]);
            }
        ],
        [
            'attribute' => 'sort',
            'format' => 'raw',
            'headerOptions' => ['class' => 'col-md-1'],
            'value' => function ($model, $key, $index, $column){
                return  Html::sort($model->sort);
            }
        ],
        [
            'header' => "操作",
            'class' => 'yii\grid\ActionColumn',
            'template'=> '{edit} {status} {delete}',
            'buttons' => [
                'edit' => function ($url, $model, $key) {
                    return Html::edit(['ajax-edit','id' => $model->id], '编辑', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]);
                },
                'status' => function ($url, $model, $key) {
                    return Html::status($model->status);
                },
                'delete' => function ($url, $model, $key) {
                    return Html::delete(['delete','id' => $model->id]);
                },
            ],
        ],
    ]
]); ?>
```

更多文档：https://github.com/leandrogehlen/yii2-treegrid



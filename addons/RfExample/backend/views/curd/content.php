<?php
use common\widgets\webuploader\Files;
use kartik\datetime\DateTimePicker;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => 'Curd', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="box-body">
    <div class="col-lg-12">
        <?= $form->field($model, 'title')->textInput(); ?>
        <?= $form->field($model, 'description')->textarea(); ?>
        <?= $form->field($model, 'tag')->widget(kartik\select2\Select2::class, [
            'data' => [1 => "First", 2 => "Second", 3 => "Third", 4 => "Fourth", 5 => "Fifth"],
            'options' => ['placeholder' => '请选择'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>
        <?= $form->field($model, 'sort')->textInput(); ?>
        <div class="row">
            <div class="col-sm-6">
                <?= $form->field($model, 'start_time')->widget(DateTimePicker::class, [
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
            </div>
            <div class="col-sm-6">
                <?= $form->field($model, 'end_time')->widget(DateTimePicker::class, [
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
            </div>
        </div>
        <?= \backend\widgets\provinces\Provinces::widget([
            'form' => $form,
            'model' => $model,
            'provincesName' => 'provinces',// 省字段名
            'cityName' => 'city',// 市字段名
            'areaName' => 'area',// 区字段名
            'template' => 'short'
        ]); ?>
        <?= $form->field($model, 'address')->widget(\backend\widgets\selectmap\Map::class, [
            'type' => 'amap', // amap高德;tencent:腾讯;baidu:百度
        ])->hint('点击地图某处才会获取到经纬度，否则默认北京'); ?>
        <?= $form->field($model, 'cover')->widget(Files::class, [
            'type' => 'images',
            'theme' => 'default',
            'themeConfig' => [],
            'config' => [
                // 可设置自己的上传地址, 不设置则默认地址
                // 'server' => '',
                'pick' => [
                    'multiple' => false,
                ],
                // 不配置则不生成缩略图
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
                    'drive' => 'local',// 默认本地 支持qiniu/oss/cos 上传
                ],
                'chunked' => false,// 开启分片上传
                'chunkSize' => 512 * 1024,// 分片大小
            ]
        ]); ?>
        <?= $form->field($model, 'covers')->widget(Files::class, [
            'type' => 'images',
            'theme' => 'default',
            'themeConfig' => [],
            'config' => [
                // 可设置自己的上传地址, 不设置则默认地址
                // 'server' => '',
                'pick' => [
                    'multiple' => true,
                ],
                'formData' => [
                    // 保留原名称
                    'originalName' => true
                ],
                'chunked' => false,// 开启分片上传
                'chunkSize' => 512 * 1024,// 分片大小
            ]
        ]); ?>
        <?= $form->field($model, 'file')->widget(Files::class, [
            'type' => 'files',
            'theme' => 'default',
            'themeConfig' => [],
            'config' => [
                'pick' => [
                    'multiple' => false,
                ],
                'formData' => [
                    // 'drive' => 'cos', // 默认本地 可修改 qiniu/oss/cos 上传
                ], // 表单参数
                'chunked' => false,// 开启分片上传
                'chunkSize' => 1024 * 1024 * 5,// 分片大小
            ]
        ]); ?>
        <?= $form->field($model, 'files')->widget(Files::class, [
            'type' => 'files',
            'theme' => 'default',
            'themeConfig' => [],
            'config' => [
                'pick' => [
                    'multiple' => true,
                ],
                'chunked' => true,// 开启分片上传
                'chunkSize' => 512 * 1024,// 分片大小
            ]
        ]); ?>
        <?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::class, [
            'formData' => [
                'drive' => 'local', // 默认本地 支持qiniu/oss/cos 上传
                'thumb' => [
                    [
                        'widget' => 100,
                        'height' => 100,
                    ],
                ]
            ],
        ]) ?>
        <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::$listExplain); ?>
    </div>
</div>
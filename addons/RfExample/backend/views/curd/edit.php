<?php
use yii\widgets\ActiveForm;
use common\helpers\AddonUrl;
use common\widgets\webuploader\Images;
use common\widgets\webuploader\Files;
use kartik\datetime\DateTimePicker;
use kartik\select2\Select2;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => 'Curd', 'url' => AddonUrl::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body">
                <div class="col-lg-12">
                    <?= $form->field($model, 'title')->textInput(); ?>
                    <?= $form->field($model, 'description')->textarea(); ?>
                    <?= $form->field($model, 'tag')->widget(Select2::classname(), [
                        'data' => [1 => "First", 2 => "Second", 3 => "Third", 4 => "Fourth", 5 => "Fifth"],
                        'options' => ['placeholder' => '请选择'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ]);?>
                    <?= $form->field($model, 'sort')->textInput(); ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'start_time')->widget(DateTimePicker::className(), [
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
                            <?= $form->field($model, 'end_time')->widget(DateTimePicker::className(), [
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
                    <?= $form->field($model, 'cover')->widget(Images::className(), [
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
                                'drive' => 'local',// 默认本地 支持qiniu/oss 上传
                            ],
                            'chunked' => false,// 开启分片上传
                            'chunkSize' => 512 * 1024,// 分片大小
                        ]
                    ]); ?>
                    <?= $form->field($model, 'covers')->widget(Images::className(), [
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
                    <?= $form->field($model, 'file')->widget(Files::className(), [
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ],
                            'chunked' => false,// 开启分片上传
                            'chunkSize' => 1024 * 1024 * 5,// 分片大小
                        ]
                    ]); ?>
                    <?= $form->field($model, 'files')->widget(Files::className(), [
                        'config' => [
                            'pick' => [
                                'multiple' => true,
                            ],
                            'chunked' => true,// 开启分片上传
                            'chunkSize' => 512 * 1024,// 分片大小
                        ]
                    ]); ?>
                    <?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::className(), [
                        'formData' => [
                            'drive' => 'local', // 默认本地 支持qiniu/oss 上传
                            'thumb' => [
                                [
                                    'widget' => 100,
                                    'height' => 100,
                                ],
                            ]
                        ],
                    ]) ?>
                    <?= $form->field($model, 'status')->radioList(['1' => '启用','0' => '禁用']); ?>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary" type="submit">保存</button>
                    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
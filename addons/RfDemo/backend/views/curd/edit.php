<?php
use yii\widgets\ActiveForm;
use common\helpers\AddonUrl;
use common\widgets\webuploader\Images;
use common\widgets\webuploader\Files;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => 'Curd', 'url' => AddonUrl::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>基本信息</h5>
            </div>
            <div class="ibox-content">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([]); ?>
                    <?= $form->field($model, 'title')->textInput(); ?>
                    <?= $form->field($model, 'description')->textarea(); ?>
                    <?= $form->field($model, 'sort')->textInput(); ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'stat_time')->widget(DateTimePicker::className(), [
                                'language' => 'zh-CN',
                                'template' => '{button}{reset}{input}',
                                'options' => [
                                    'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',$model->stat_time),
                                ],
                                'clientOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii:ss',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ]
                            ]);?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'end_time')->widget(DateTimePicker::className(), [
                                'language' => 'zh-CN',
                                'template' => '{button}{reset}{input}',
                                'options' => [
                                    'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',$model->end_time),
                                ],
                                'clientOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii:ss',
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
                            //可设置自己的上传地址, 不设置则默认地址
                            // 'server' => Url::to(['/file/qiniu']),//七牛上传 (二选一)
                            // 'server' => Url::to(['/file/ali-oss']),//阿里云Oss上传
                            'pick' => [
                                'multiple' => false,
                            ],
                            // 不配置则不生成缩略图
                            'formData' => [
                                'thumbWidget' => 100, // 缩略图宽度 px
                                'thumbHeight' => 100, // 缩略图高度 px
                            ],
                            'chunked' => false,// 开启分片上传
                            'chunkSize' => 512 * 1024,// 分片大小
                        ]
                    ]); ?>
                    <?= $form->field($model, 'covers')->widget(Images::className(), [
                        'config' => [
                            //可设置自己的上传地址, 不设置则默认地址
                            // 'server' => Url::to(['/file/qiniu']),//七牛上传 (二选一)
                            // 'server' => Url::to(['/file/ali-oss']),//阿里云Oss上传
                            'pick' => [
                                'multiple' => true,
                            ],
                            // 不配置则不生成缩略图
                            'formData' => [
                                // 'thumbWidget' => 100, // 缩略图宽度 px
                                // 'thumbHeight' => 100, // 缩略图高度 px
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
                        ]
                    ]); ?>
                    <?= $form->field($model, 'files')->widget(Files::className(), [
                        'config' => [
                            'pick' => [
                                'multiple' => true,
                            ],
                        ]
                    ]); ?>
                    <?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::className()) ?>
                    <?= $form->field($model, 'status')->radioList(['1' => '启用','0' => '禁用']); ?>
                    <div class="hr-line-dashed"></div>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary" type="submit">保存</button>
                        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
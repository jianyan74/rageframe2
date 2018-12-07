<?php
use yii\widgets\ActiveForm;
use common\helpers\AddonUrl;
use common\widgets\webuploader\Images;
use common\widgets\webuploader\Files;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => 'ElasticSearch', 'url' => AddonUrl::to(['index'])];
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
                    <?= $form->field($model, 'sort')->textInput(); ?>
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
                                ]
                            ],
                            'chunked' => false,// 开启分片上传
                            'chunkSize' => 512 * 1024,// 分片大小
                        ]
                    ]); ?>
                    <?= $form->field($model, 'content')->textarea(); ?>
                    <?= $form->field($model, 'status')->radioList(['1' => '启用','0' => '禁用']); ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <div class="hr-line-dashed"></div>
                        <button class="btn btn-primary" type="submit">保存</button>
                        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
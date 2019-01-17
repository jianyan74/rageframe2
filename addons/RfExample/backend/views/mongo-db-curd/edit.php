<?php
use yii\widgets\ActiveForm;
use common\helpers\AddonUrl;
use common\widgets\webuploader\Images;
use common\widgets\webuploader\Files;
use kartik\datetime\DateTimePicker;

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
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ]
            ]); ?>
            <div class="box-body">
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
                    ]
                ]); ?>
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'longitude')->textInput(); ?>
                    </div>
                    <div class="col-sm-6">
                        <?= $form->field($model, 'latitude')->textInput(); ?>
                    </div>
                </div>
                <?= $form->field($model, 'status')->radioList(['1' => '启用','0' => '禁用']); ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
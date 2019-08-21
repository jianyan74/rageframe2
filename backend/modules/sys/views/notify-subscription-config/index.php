<?php

use yii\widgets\ActiveForm;
use common\enums\StatusEnum;

$this->title = '提醒管理';
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
                <?= $form->field($model, 'behavior_warning')->radioList(StatusEnum::$listExplain) ?>
                <?= $form->field($model, 'behavior_error')->radioList(StatusEnum::$listExplain) ?>
                <?= $form->field($model, 'log_warning')->radioList(StatusEnum::$listExplain) ?>
                <?= $form->field($model, 'log_error')->radioList(StatusEnum::$listExplain) ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary" type="submit" onclick="SendForm()">保存</button>
                    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
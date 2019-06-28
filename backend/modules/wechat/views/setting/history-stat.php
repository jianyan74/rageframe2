<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;

$this->title = '参数配置';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ]
            ]); ?>
            <div class="box-body">
                <div class="form-group m-b-md">
                    <div class="col-sm-2 text-right">
                        <label class="control-label">微信消息接收地址</label>
                    </div>
                    <div class="col-sm-10">
                        <div class="hint-block"><?= Url::toWechat(['api/index']) ?></div>
                        <div class="help-block"></div>
                    </div>
                </div>
                <?= $form->field($model, 'history_status')->radioList([1 => '开启', 0 => '关闭'])->hint('开启此项后，系统将记录用户与系统的往来消息记录。') ?>
                <?= $form->field($model, 'msg_history_date')->textInput()->hint('设置保留历史消息记录的天数，为0则为保留全部，需要开启定时任务。') ?>
                <?= $form->field($model, 'utilization_status')->radioList([1 => '开启', 0 => '关闭'])->hint('开启此项后，系统将记录系统中的规则的使用情况，并生成走势图。') ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
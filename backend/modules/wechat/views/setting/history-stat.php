<?php
use yii\widgets\ActiveForm;

$this->title = '参数';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox-content">
                <?php $form = ActiveForm::begin([
                    'options' => [
                        'enctype' => 'multipart/form-data'
                    ],
                    'fieldConfig' => [
                        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                    ]
                ]); ?>
                <?= $form->field($model, 'history_status')->radioList([1 => '开启', 0 => '关闭'])->hint('开启此项后，系统将记录用户与系统的往来消息记录。') ?>
                <?= $form->field($model, 'msg_history_date')->textInput()->hint('设置保留历史消息记录的天数，为0则为保留全部，需要开启定时任务。') ?>
                <?= $form->field($model, 'utilization_status')->radioList([1 => '开启', 0 => '关闭'])->hint('开启此项后，系统将记录系统中的规则的使用情况，并生成走势图。') ?>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary" type="submit">保存</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
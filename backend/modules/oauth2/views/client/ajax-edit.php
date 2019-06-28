<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'class' => 'form-horizontal',
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'client_id')->textInput([
            'readonly' => !empty($model->client_id)
        ])->hint('创建后不可修改') ?>
        <?= $form->field($model, 'client_secret')->textInput() ?>
        <?= $form->field($model, 'redirect_uri')->textInput() ?>
        <?= $form->field($model, 'remark')->textarea() ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::$listExplain) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
</div>

<?php ActiveForm::end(); ?>
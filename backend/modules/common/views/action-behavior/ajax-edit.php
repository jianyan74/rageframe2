<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\MethodEnum;

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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'app_id')->dropDownList(\common\enums\AppEnum::getMap()) ?>
        <?= $form->field($model, 'url')->textInput()->hint('例如：site/logout') ?>
        <?= $form->field($model, 'behavior')->textInput()->hint('方便查找的别名，例如：logout') ?>
        <?= $form->field($model, 'remark')->textInput() ?>
        <?= $form->field($model, 'method')->dropDownList(MethodEnum::getMap()) ?>
        <?= $form->field($model, 'is_record_post')->radioList(\common\enums\WhetherEnum::getMap()) ?>
        <?= $form->field($model, 'level')->dropDownList(\common\enums\MessageLevelEnum::getMap()) ?>
        <?= $form->field($model, 'action')->radioList($actionExplain) ?>
        <?= $form->field($model, 'is_ajax')->radioList($ajaxExplain) ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
</div>

<?php ActiveForm::end(); ?>
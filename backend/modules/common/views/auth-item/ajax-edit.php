<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

/** @var ActiveForm $form */
/** @var \yii\db\ActiveRecord $model */

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'pid')->dropDownList($dropDownList) ?>
        <?= $form->field($model, 'name')->textInput()->hint('如果是路由要绝对路径哦, 例如 /main/index'); ?>
        <?= $form->field($model, 'title')->textInput(); ?>
        <?= $form->field($model, 'sort')->textInput(); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
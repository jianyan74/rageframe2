<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => false,
    'validationUrl' => Url::to(['pass', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ],
]);

?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span>
        </button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'transfer_type')->dropDownList(\addons\TinyDistribution\common\enums\TransferTypeEnum::getMap()); ?>
        <?= $form->field($model, 'transfer_name')->textInput(); ?>
        <?= $form->field($model, 'transfer_account_no')->textInput(); ?>
        <?= $form->field($model, 'transfer_no')->textInput(); ?>
        <?= $form->field($model, 'transfer_money')->textInput(); ?>
        <?= $form->field($model, 'memo')->textarea(); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
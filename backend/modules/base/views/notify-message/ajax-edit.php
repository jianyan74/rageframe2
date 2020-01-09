<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit']),
]);
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'toManagerId')->widget(\kartik\select2\Select2::class, [
            'data' => $model->data,
            'options' => ['placeholder' => '查询用户'],
            'pluginOptions' => [
                'allowClear' => true
            ],
        ]);?>
        <?= $form->field($model, 'content')->textarea() ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>

<?php Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>
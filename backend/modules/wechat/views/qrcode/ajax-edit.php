<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['validate-form']),
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h4 class="modal-title">编辑二维码</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'name')->textInput(['readonly'=>'readonly']) ?>
        <?= $form->field($model, 'keyword')->textInput()->hint('二维码对应关键字, 用户扫描后系统将通过场景ID返回关键字到平台处理.')?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>

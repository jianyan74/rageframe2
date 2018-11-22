<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['ajax-edit','id' => $model['id']]),
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'realname')->textInput() ?>
        <?= $form->field($model, 'mobile')->textInput() ?>
        <?= \backend\widgets\provinces\Provinces::widget([
            'form' => $form,
            'model' => $model,
            'provincesName' => 'provinces',// 省字段名
            'cityName' => 'city',// 市字段名
            'areaName' => 'area',// 区字段名
            // 'template' => 'short' //合并为一行显示
        ]); ?>
        <?= $form->field($model, 'detailed_address')->textarea() ?>
        <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::$listExplain) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
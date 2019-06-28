<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'realname')->textInput() ?>
        <?= $form->field($model, 'mobile')->textInput() ?>
        <?= \backend\widgets\provinces\Provinces::widget([
            'form' => $form,
            'model' => $model,
            'provincesName' => 'province_id',// 省字段名
            'cityName' => 'city_id',// 市字段名
            'areaName' => 'area_id',// 区字段名
            'template' => 'short' //合并为一行显示
        ]); ?>
        <?= $form->field($model, 'address_details')->textarea() ?>
        <?= $form->field($model, 'is_default')->checkbox() ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::$listExplain) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
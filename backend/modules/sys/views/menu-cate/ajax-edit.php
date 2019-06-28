<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;

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
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'icon')->textInput()->hint('详情请参考：<a href="http://fontawesome.dashgame.com" target="_blank">http://fontawesome.dashgame.com</a>')?>
        <?= $form->field($model, 'sort')->textInput() ?>
        <?= $form->field($model, 'is_default_show')->radioList(WhetherEnum::$listExplain)->hint('默认菜单导航显示') ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::$listExplain) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
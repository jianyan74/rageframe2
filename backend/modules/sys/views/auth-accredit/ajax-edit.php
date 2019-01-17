<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\models\sys\AuthRule;
?>

<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['ajax-edit','name' => $model['name']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]); ?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">上级目录:<?= $parent_title?></h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'description')->textInput() ?>
        <?= $form->field($model, 'name')->textInput()->hint('例如 /main/index, 要绝对路径哦') ?>
        <?= $form->field($model, 'rule_name')->dropDownList(AuthRule::getRoutes(), ['prompt' => '请选择']) ?>
        <?= $form->field($model, 'sort')->textInput() ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
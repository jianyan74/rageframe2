<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use unclead\multipleinput\MultipleInput;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::toRoute(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">上级目录：<?= $parent_title?></h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'title')->textInput() ?>
        <?= $form->field($model, 'url')->textInput()->hint("例如：/index/index，要绝对路由哦") ?>
        <?= $form->field($model, 'params')->widget(MultipleInput::className(), [
            'max' => 6,
            'columns' => [
                [
                    'name'  => 'key',
                    'title' => '参数名',
                    'enableError' => false,
                    'options' => [
                        'class' => 'input-priority'
                    ]
                ],
                [
                    'name'  => 'value',
                    'title' => '参数值',
                    'enableError' => false,
                    'options' => [
                        'class' => 'input-priority'
                    ]
                ],
            ]
        ])->label(false);
        ?>
        <?= $form->field($model, 'menu_css')->textInput()->hint('详情请参考：<a href="http://fontawesome.dashgame.com" target="_blank">http://fontawesome.dashgame.com</a>')?>
        <?= $form->field($model, 'sort')->textInput() ?>
        <?= $form->field($model, 'dev')->radioList(WhetherEnum::$listExplain)->hint('去 网站设置->系统设置 里面开启或关闭开发模式,开启后才可显示该菜单') ?>
        <?= $form->field($model, 'status')->radioList(StatusEnum::$listExplain) ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>
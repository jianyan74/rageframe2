<?php
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body" style="min-height: 160px">
        <?= $form->field($model, 'time')->widget(kartik\time\TimePicker::class, [
            'language' => 'zh-CN',
            'pluginOptions' => [
                'showSeconds' => true
            ]
        ]);?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    </div>

<?php \common\helpers\Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>
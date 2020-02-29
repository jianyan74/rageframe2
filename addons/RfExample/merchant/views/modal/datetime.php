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
    <div class="modal-body">
        <?= $form->field($model, 'start_time')->widget(kartik\datetime\DateTimePicker::class, [
            'language' => 'zh-CN',
            'options' => [
                'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s',$model->start_time),
            ],
            'pluginOptions' => [
                'format' => 'yyyy-mm-dd hh:ii',
                'todayHighlight' => true, // 今日高亮
                'autoclose' => true, // 选择后自动关闭
                'todayBtn' => true, // 今日按钮显示
            ]
        ]);?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    </div>

<?php \common\helpers\Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>
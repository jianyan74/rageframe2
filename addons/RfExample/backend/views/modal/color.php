<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>
    <style>
        .sp-replacer {
            background: #fff;
        }
        .spectrum-group > .input-group-addon {
            padding: 0;
        }
    </style>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'color')->widget(kartik\color\ColorInput::class, [
            'options' => ['placeholder' => '请选择颜色'],
        ]);?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    </div>

    <script>
        // 清理颜色
        $('#ajaxModal').on('hide.bs.modal', function () {
            $('#edui_fixedlayer').remove();
            $('#global-zeroclipboard-html-bridge').remove();
            $('.sp-container').remove();
        });
    </script>
<?php \common\helpers\Html::modelBaseCss(); ?>
<?php ActiveForm::end(); ?>
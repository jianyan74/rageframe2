<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use kartik\color\ColorInput;

$form = ActiveForm::begin([
    'id' => $model->formName(),
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'content')->widget(\common\widgets\ueditor\UEditor::class, [
            'formData' => [
                'drive' => 'local', // 默认本地 支持qiniu/oss 上传
                'thumb' => [
                    [
                        'width' => 100,
                        'height' => 100,
                    ],
                ]
            ],
        ]) ?>
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
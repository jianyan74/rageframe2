<?php
use yii\widgets\ActiveForm;

$form = ActiveForm::begin([
    'id' => $model->formName(),
]);
?>
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= \backend\widgets\provinces\Provinces::widget([
            'form' => $form,
            'model' => $model,
            'provincesName' => 'provinces',// 省字段名
            'cityName' => 'city',// 市字段名
            'areaName' => 'area',// 区字段名
            'template' => 'short'
        ]); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    </div>
<?php ActiveForm::end(); ?>
<?php

use yii\widgets\ActiveForm;
use common\helpers\ArrayHelper;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
    <h4 class="modal-title">消息群发</h4>
</div>
<div class="modal-body">
    <?= $form->field($model, 'tag_id')->dropDownList(ArrayHelper::merge([-1 => '全部粉丝'],
        ArrayHelper::map($tags, 'id', 'name'))) ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>

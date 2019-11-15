<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\Html;
use yii\helpers\ArrayHelper;

?>

<?php $form = ActiveForm::begin([]); ?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">用户标签</h4>
</div>
<div class="modal-body">
    <?= Html::checkboxList('tag_id', $fansTags, ArrayHelper::map($tags, 'id', 'name')); ?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>

<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\AccountTypeEnum;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
]);

?>

    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
        <h4 class="modal-title">基本信息</h4>
    </div>
    <div class="modal-body">
        <?= $form->field($model, 'realname')->textInput(); ?>
        <?= $form->field($model, 'mobile')->textInput(); ?>
        <?= $form->field($model, 'account_type')->radioList(AccountTypeEnum::getMap()); ?>
        <div class="<?= $model->account_type != 1 ? 'hide' : ''; ?>" id="bank">
            <?= $form->field($model, 'branch_bank_name')->textInput(); ?>
            <?= $form->field($model, 'account_number')->textInput(); ?>
        </div>
        <div class="<?= $model->account_type != 3 ? 'hide' : ''; ?>" id="ali">
            <?= $form->field($model, 'ali_number')->textInput(); ?>
        </div>
        <?= $form->field($model, 'is_default')->checkbox(); ?>
    </div>
    <div class="modal-footer">
        <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
        <button class="btn btn-primary" type="submit">保存</button>
    </div>
<?php ActiveForm::end(); ?>

<script>
    $("input[name='BankAccountForm[account_type]']").click(function () {
        var val = $(this).val();
        if (parseInt(val) === 1) {
            $('#ali').addClass('hide');
            $('#bank').removeClass('hide');
        } else {
            $('#bank').addClass('hide');
            $('#ali').removeClass('hide');
        }
    });
</script>

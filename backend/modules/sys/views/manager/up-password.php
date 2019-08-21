<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

$this->title = '修改密码';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'id' => 'pwd',
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'passwd')->passwordInput() ?>
                <?= $form->field($model, 'passwd_new')->passwordInput() ?>
                <?= $form->field($model, 'passwd_repetition')->passwordInput() ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    var $form = $('#pwd');
    $form.on('beforeSubmit', function () {
        var data = $form.serialize();

        $.ajax({
            type: "post",
            url: "<?= Url::to(['up-password']); ?>",
            dataType: "json",
            data: data,
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    parent.location.reload();
                    window.location.reload();
                } else {
                    rfWarning(data.message);
                }
            }
        });

        return false; // 防止默认提交
    });
</script>
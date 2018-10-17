<?php
use yii\widgets\ActiveForm;

$this->params['breadcrumbs'][] = ['label' => '修改密码'];
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row col-sm-offset-2">
        <div class="col-sm-9">
            <div class="ibox float-e-margins">
                <div class="ibox-content">
                    <?php $form = ActiveForm::begin([
                        'fieldConfig' => [
                            'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                        ]
                    ]); ?>
                    <div class="col-sm-12">
                        <?= $form->field($model, 'passwd')->passwordInput() ?>
                        <?= $form->field($model, 'passwd_new')->passwordInput() ?>
                        <?= $form->field($model, 'passwd_repetition')->passwordInput() ?>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <div class="hr-line-dashed"></div>
                            <button class="btn btn-primary" type="submit">保存</button>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

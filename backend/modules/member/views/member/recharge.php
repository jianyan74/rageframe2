<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\helpers\ImageHelper;
use backend\modules\member\forms\RechargeForm;

?>

<div class="modal-content">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
        </button>
        <h4 class="modal-title">充值</h4>
    </div>
    <div class="modal-body">

        <div class="col-md-12">
            <div class="col-sm-6 invoice-col">
                <p class="lead"></p>
                <address class="p-m">
                    <img src="<?= ImageHelper::defaultHeaderPortrait($model->head_portrait) ?>" class="img-circle img-bordered-sm" width="48" height="48">
                </address>
            </div>
            <div class="col-sm-6 invoice-col">
                <address>
                    <strong><?= $model['nickname'] ?></strong><br>
                    ID: <?= $model['id'] ?><br>
                    昵称: <?= $model['realname'] ?><br>
                    姓名: <?= $model['realname'] ?><br>
                    手机号码: <?= $model['mobile'] ?>
                </address>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-12">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">充值积分</a></li>
                        <li><a data-toggle="tab" href="#tab-2" aria-expanded="false">充值余额</a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="tab-1" class="tab-pane active">
                            <div class="panel-body">
                                <?php $form = ActiveForm::begin([
                                    'id' => $rechargeForm->formName(),
                                    'enableAjaxValidation' => true,
                                    'class' => 'form-horizontal',
                                    'validationUrl' => Url::to(['recharge', 'id' => $model->id]),
                                    'fieldConfig' => [
                                        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                                    ]
                                ]); ?>
                                <?= $form->field($rechargeForm, 'old_num')->textInput([
                                    'value' => $model['user_integral'],
                                    'readonly' => 'readonly'
                                ]) ?>
                                <?= $form->field($rechargeForm, 'change')->radioList(RechargeForm::$changeExplain) ?>
                                <?= $form->field($rechargeForm, 'int')->textInput() ?>
                                <?= $form->field($rechargeForm, 'remark')->textarea() ?>
                                <?= $form->field($rechargeForm,
                                    'type')->hiddenInput(['value' => RechargeForm::TYPE_INT])->label(false) ?>
                                <div class="box-footer">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-primary" type="submit">确认</button>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <div id="tab-2" class="tab-pane">
                            <div class="panel-body">
                                <?php $form = ActiveForm::begin([
                                    'id' => 'money',
                                    'enableAjaxValidation' => true,
                                    'class' => 'form-horizontal',
                                    'validationUrl' => Url::to(['recharge', 'id' => $model->id]),
                                    'fieldConfig' => [
                                        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
                                    ]
                                ]); ?>
                                <?= $form->field($rechargeForm, 'old_num')->textInput([
                                    'value' => $model['user_money'],
                                    'readonly' => 'readonly'
                                ]) ?>
                                <?= $form->field($rechargeForm, 'change')->radioList(RechargeForm::$changeExplain) ?>
                                <?= $form->field($rechargeForm, 'money')->textInput() ?>
                                <?= $form->field($rechargeForm, 'remark')->textarea() ?>
                                <?= $form->field($rechargeForm,
                                    'type')->hiddenInput(['value' => RechargeForm::TYPE_MONEY])->label(false) ?>
                                <div class="box-footer">
                                    <div class="col-sm-12 text-center">
                                        <button class="btn btn-primary" type="submit">确认</button>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
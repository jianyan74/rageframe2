<?php

use yii\widgets\ActiveForm;
use common\enums\AccountTypeEnum;

$this->title = '基础设置';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>
<?php $form = ActiveForm::begin([]); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true">提现设置</a></li>
                <li class="hide"><a data-toggle="tab" href="#tab-2" aria-expanded="false">相关协议</a></li>
            </ul>
            <div class="tab-content">
                <div id="tab-1" class="tab-pane active">
                    <div class="panel-body">
                        <?= $form->field($model, 'withdraw_lowest_money')->textInput(); ?>
                        <?= $form->field($model, 'withdraw_account')->checkboxList(AccountTypeEnum::getMap()); ?>
                        <?= $form->field($model, 'withdraw_is_open')->radioList([0 => '关闭', 1 => '开启']); ?>
                    </div>
                </div>
                <div id="tab-2" class="tab-pane">
                    <div class="panel-body">
                        <?= $form->field($model, 'protocol_cooperation')->widget(\common\widgets\ueditor\UEditor::class); ?>
                    </div>
                </div>
                <div class="box-footer text-center">
                    <button class="btn btn-primary" type="submit">保存</button>
                    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

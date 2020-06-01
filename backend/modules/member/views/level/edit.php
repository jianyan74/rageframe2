<?php

use yii\widgets\ActiveForm;
use common\enums\GenderEnum;
use common\enums\StatusEnum;

$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '等级信息', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ],
            ]); ?>
            <div class="box-body">
                <?php if($model->isNewRecord || ($model->level != 1 && !$model->isNewRecord)) {?>
                    <?= $form->field($model, 'level')->widget(\kartik\select2\Select2::class, [
                        'data' => \common\enums\MemberLevelEnum::getMap(),
                        'options' => ['placeholder' => '请选择'],
                        'pluginOptions' => [
                            'allowClear' => true
                        ],
                    ])->hint('数字越大等级越高'); ?>
                <?php } ?>
                <?= $form->field($model, 'name')->textInput() ?>
                <?= $form->field($model, 'discount')->textInput()->hint('打几折') ?>
                <?= $form->field($model, 'detail')->textarea() ?>
                <div class="form-group field-goods-fictitious_view">
                    <div class="col-sm-2 text-right">
                        <label class="control-label" for="goods-fictitious_view">升级条件</label>
                    </div>
                    <div class="col-sm-10 specification">
                        <div class="form-inline">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        累计积分满
                                    </label>
                                </div>
                                <input type="number" name="Level[integral]" value="<?= $model->integral ?>"
                                       step="1" min="0" class="form-control">
                                <label class="small" style="color: #777;"> *设置会员等级所需要的累计积分且必须大于等于0</label>
                            </div>
                        </div>

                        <br/>

                        <div class="form-inline">
                            <div class="form-group">
                                <div class="checkbox">
                                    <label>
                                        消费额度满
                                    </label>
                                </div>
                                <input type="number" name="Level[money]" value="<?= $model->money ?>"
                                       step="0.01" min="0" class="form-control">
                                <label class="small" style="color: #777;"> *设置会员等级所需要的累计积分且必须大于等于0</label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
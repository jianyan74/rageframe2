<?php
use yii\widgets\ActiveForm;
use common\helpers\AddonUrl;
use common\widgets\webuploader\Images;
use common\widgets\webuploader\Files;
use kartik\datetime\DateTimePicker;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '奖品管理', 'url' => AddonUrl::to(['index'])];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body">
                <div class="col-lg-12">
                    <?= $form->field($model, 'title')->textInput(); ?>
                    <?= $form->field($model, 'sort')->textInput(); ?>
                    <?= $form->field($model, 'prob')->textInput()->hint('最高总上限为 1000 请不要超出'); ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'start_time')->widget(DateTimePicker::className(), [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $model->start_time),
                                ],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ]
                            ]);?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'end_time')->widget(DateTimePicker::className(), [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $model->end_time),
                                ],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ]
                            ]);?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'draw_start_time')->widget(DateTimePicker::className(), [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $model->draw_start_time),
                                ],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ]
                            ]);?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'draw_end_time')->widget(DateTimePicker::className(), [
                                'language' => 'zh-CN',
                                'options' => [
                                    'value' => $model->isNewRecord ? date('Y-m-d H:i:s') : date('Y-m-d H:i:s', $model->draw_end_time),
                                ],
                                'pluginOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ]
                            ]);?>
                        </div>
                    </div>
                    <?= $form->field($model, 'cate_id')->radioList(['1' => '积分','2' => '卡卷']); ?>
                    <div class="row">
                        <div class="col-sm-6"><?= $form->field($model, 'all_num')->textInput(); ?></div>
                        <div class="col-sm-6"><?= $form->field($model, 'surplus_num')->textInput(); ?></div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6"><?= $form->field($model, 'max_day_num')->textInput(); ?></div>
                        <div class="col-sm-6"><?= $form->field($model, 'max_user_num')->textInput(); ?></div>
                    </div>
                    <?= $form->field($model, 'status')->radioList(['1' => '启用','0' => '禁用']); ?>
                </div>
                <div class="box-footer text-center">
                    <button class="btn btn-primary" type="submit">保存</button>
                    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
<?php

use yii\widgets\ActiveForm;
use common\enums\GenderEnum;
use common\enums\StatusEnum;

$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '第三方用户', 'url' => ['index']];
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
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'nickname')->textInput() ?>
                <?= $form->field($model, 'oauth_client')->textInput([
                    'readonly' => 'readonly'
                ]) ?>
                <?= $form->field($model, 'gender')->radioList(GenderEnum::$listExplain) ?>
                <?= $form->field($model, 'head_portrait')->widget(common\widgets\webuploader\Files::class, [
                    'type' => 'images',
                    'theme' => 'default',
                    'themeConfig' => [],
                    'config' => [
                        'pick' => [
                            'multiple' => false,
                        ],
                    ]
                ]); ?>
                <?= $form->field($model, 'country')->textInput() ?>
                <?= $form->field($model, 'province')->textInput() ?>
                <?= $form->field($model, 'city')->textInput() ?>
                <?= $form->field($model, 'birthday')->widget('kartik\date\DatePicker', [
                    'language' => 'zh-CN',
                    'layout' => '{picker}{input}',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,// 今日高亮
                        'autoclose' => true,// 选择后自动关闭
                        'todayBtn' => true,// 今日按钮显示
                    ],
                    'options' => [
                        'class' => 'form-control no_bor',
                    ]
                ]); ?>
                <?= $form->field($model, 'status')->radioList(StatusEnum::$listExplain) ?>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary" type="submit">保存</button>
                    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
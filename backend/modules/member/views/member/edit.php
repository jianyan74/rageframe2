<?php

use yii\widgets\ActiveForm;
use common\enums\GenderEnum;
use common\enums\StatusEnum;

$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '会员信息', 'url' => ['index']];
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
                <?= $form->field($model, 'realname')->textInput() ?>
                <?= $form->field($model, 'nickname')->textInput() ?>
                <?= $form->field($model, 'mobile')->textInput() ?>
                <?= $form->field($model, 'gender')->radioList(GenderEnum::$listExplain) ?>
                <?= $form->field($model, 'head_portrait')->widget('common\widgets\webuploader\Files', [
                    'type' => 'images',
                    'theme' => 'default',
                    'themeConfig' => [],
                    'config' => [
                        'pick' => [
                            'multiple' => false,
                        ],
                    ]
                ]); ?>
                <?= $form->field($model, 'qq')->textInput() ?>
                <?= $form->field($model, 'email')->textInput() ?>
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
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
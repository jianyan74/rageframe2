<?php
use common\helpers\Url;
use yii\helpers\Url as BaseUrl;
use yii\widgets\ActiveForm;
use common\helpers\Html;
use common\enums\GenderEnum;
use common\helpers\ImageHelper;
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
                <?= $form->field($model, 'head_portrait')->widget(\backend\widgets\cropper\Cropper::class, []); ?>
                <?= $form->field($model, 'realname')->textInput() ?>
                <?= $form->field($model, 'gender')->radioList(GenderEnum::$listExplain) ?>
                <?= $form->field($model, 'mobile')->textInput() ?>
                <?= \backend\widgets\provinces\Provinces::widget([
                    'form' => $form,
                    'model' => $model,
                    'provincesName' => 'province_id',// 省字段名
                    'cityName' => 'city_id',// 市字段名
                    'areaName' => 'area_id',// 区字段名
                ]); ?>
                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model,'birthday')->widget('kartik\date\DatePicker',[
                    'language'  => 'zh-CN',
                    'layout'=>'{picker}{input}',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,// 今日高亮
                        'autoclose' => true,// 选择后自动关闭
                        'todayBtn' => true,// 今日按钮显示
                    ],
                    'options'=>[
                        'class' => 'form-control no_bor',
                    ]
                ]); ?>
                <?= $form->field($model, 'address')->textarea() ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit" onclick="sendForm()">保存</button>
                <?= $backBtn ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
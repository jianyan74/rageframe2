<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;

$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '商户管理', 'url' => ['merchant/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-card green"></i> <?= $model['account']['user_money'] ?? 0 ?></span>
                <span class="info-box-text">剩余余额(元)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-ios-pulse red"></i> <?= $model['account']['accumulate_money'] - $model['account']['user_money'] ?></span>
                <span class="info-box-text">累计提现(元)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <div class="col-md-4 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i class="icon ion-arrow-graph-up-right yellow"></i> <?= $model['account']['accumulate_money'] ?? 0 ?></span>
                <span class="info-box-text">累计余额(元)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-4 text-right'>{label}</div><div class='col-sm-8'>{input}\n{hint}\n{error}</div>",
                ],
            ]); ?>
            <div class="box-body">
                <div class="col-lg-6">
                    <?= $form->field($model, 'company_name')->textInput([
                            'disabled' => true
                    ]) ?>
                    <?= $form->field($model, 'title')->textInput([
                        'disabled' => true
                    ]) ?>
                    <?= $form->field($model, 'cate_id')->dropDownList($cates, [
                        'prompt' => '请选择',
                        'disabled' => true
                    ]) ?>
                    <?= $form->field($model, 'cover')->widget(\common\widgets\cropper\Cropper::class, []); ?>
                    <?= $form->field($model, 'mobile')->textInput() ?>
                    <?= \common\widgets\provinces\Provinces::widget([
                        'form' => $form,
                        'model' => $model,
                        'provincesName' => 'province_id',// 省字段名
                        'cityName' => 'city_id',// 市字段名
                        'areaName' => 'area_id',// 区字段名
                    ]); ?>
                    <?= $form->field($model, 'address_details')->textarea() ?>
                    <?= $form->field($model, 'business_time')->textInput() ?>
                    <?= $form->field($model, 'free_time')->textInput() ?>
                </div>
                <div class="col-lg-6">
                    <?= $form->field($model, 'logo')->widget(\common\widgets\webuploader\Files::class, [
                        'type' => 'images',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ],
                        ],
                    ]); ?>
                    <?= $form->field($model, 'banner')->widget(\common\widgets\webuploader\Files::class, [
                        'type' => 'images',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ],
                        ],
                    ]); ?>
                    <?= $form->field($model, 'qrcode')->widget(\common\widgets\webuploader\Files::class, [
                        'type' => 'images',
                        'theme' => 'default',
                        'themeConfig' => [],
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ],
                        ],
                    ]); ?>
                    <?= $form->field($model, 'email')->textInput() ?>
                    <?= $form->field($model, 'qq')->textInput() ?>
                    <?= $form->field($model, 'ww')->textInput() ?>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit" onclick="sendForm()">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

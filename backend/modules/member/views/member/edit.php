<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

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
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'realname')->textInput() ?>
                <?= $form->field($model, 'nickname')->textInput() ?>
                <?= $form->field($model, 'mobile_phone')->textInput() ?>
                <?= $form->field($model, 'sex')->radioList(['1' => '男', '2' => '女']) ?>
                <?= $form->field($model, 'head_portrait')->widget('common\widgets\webuploader\Images', [
                    'config' => [
                        // 可设置自己的上传地址, 不设置则默认地址
                        // 'server' => '',
                        'pick' => [
                            'multiple' => false,
                        ],
                        'formData' => [
                            // 不配置则不生成缩略图
                            // 'thumb' => [
                            //     [
                            //         'widget' => 100,
                            //         'height' => 100,
                            //     ],
                            // ]
                        ],
                        'chunked' => false,// 开启分片上传
                        'chunkSize' => 512 * 1024,// 分片大小
                    ]
                ]);?>
                <?= $form->field($model, 'qq')->textInput() ?>
                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model, 'birthday')->widget('kartik\date\DatePicker',[
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
                        'readonly' => 'readonly',// 禁止输入
                    ]
                ]); ?>
                <?= $form->field($model, 'status')->radioList(\common\enums\StatusEnum::$listExplain) ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
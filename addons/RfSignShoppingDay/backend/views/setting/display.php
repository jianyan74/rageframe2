<?php
use yii\widgets\ActiveForm;
use common\widgets\webuploader\Files;
use common\helpers\Url;
use kartik\datetime\DateTimePicker;

$this->title = '参数设置';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?php $form = ActiveForm::begin([]); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'site_title')->textInput(); ?>
                <div class="row">
                    <div class="col-sm-6">
                        <?= $form->field($model, 'start_time')->widget(DateTimePicker::class, [
                            'language' => 'zh-CN',
                            'options' => [
                                'value' => empty($model->start_time) ? date('Y-m-d H:i:s') : $model->start_time,
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
                        <?= $form->field($model, 'end_time')->widget(DateTimePicker::class, [
                            'language' => 'zh-CN',
                            'options' => [
                                'value' => empty($model->end_time) ? date('Y-m-d H:i:s') : $model->end_time,
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
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">微信分享设置</h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'share_title')->textInput(); ?>
                <?= $form->field($model, 'share_desc')->textarea(); ?>
                <?= $form->field($model, 'share_cover')->widget(Files::class, [
                    'config' => [
                        'pick' => [
                            'multiple' => false,
                        ],
                    ]
                ]); ?>
                <?= $form->field($model, 'share_link')->textInput(); ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>
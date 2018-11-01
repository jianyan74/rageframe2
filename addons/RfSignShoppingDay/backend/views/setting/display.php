<?php
use yii\widgets\ActiveForm;
use common\widgets\webuploader\Images;
use common\helpers\AddonUrl;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = '参数设置';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?php $form = ActiveForm::begin([]); ?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>基本设置</h5>
            </div>
            <div class="ibox-content">
                <div class="col-sm-12">
                    <?= $form->field($model, 'site_title')->textInput(); ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <?= $form->field($model, 'start_time')->widget(DateTimePicker::className(), [
                                'language' => 'zh-CN',
                                'template' => '{button}{reset}{input}',
                                'options' => [
                                    'value' => empty($model->start_time) ? date('Y-m-d H:i:s') : $model->start_time,
                                ],
                                'clientOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii:ss',
                                    'todayHighlight' => true,//今日高亮
                                    'autoclose' => true,//选择后自动关闭
                                    'todayBtn' => true,//今日按钮显示
                                ]
                            ]);?>
                        </div>
                        <div class="col-sm-6">
                            <?= $form->field($model, 'end_time')->widget(DateTimePicker::className(), [
                                'language' => 'zh-CN',
                                'template' => '{button}{reset}{input}',
                                'options' => [
                                    'value' => empty($model->end_time) ? date('Y-m-d H:i:s') : $model->end_time,
                                ],
                                'clientOptions' => [
                                    'format' => 'yyyy-mm-dd hh:ii:ss',
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
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>微信分享设置</h5>
            </div>
            <div class="ibox-content">
                <div class="col-sm-12">
                    <?= $form->field($model, 'share_title')->textInput(); ?>
                    <?= $form->field($model, 'share_cover')->widget(Images::className(), [
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ],
                        ]
                    ]); ?>
                    <?= $form->field($model, 'share_link')->textInput(); ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <div class="hr-line-dashed"></div>
                        <button class="btn btn-primary" type="submit">保存</button>
                        <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

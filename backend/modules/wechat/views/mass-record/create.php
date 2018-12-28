<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '定时群发', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="wrapper wrapper-content animated fadeInRight">
    <?php $form = ActiveForm::begin([]); ?>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5>基本信息</h5>
                </div>
                <div class="ibox-content">
                    <div class="col-md-12">
                        <?= $form->field($model, 'tag_id')->dropDownList(\common\helpers\ArrayHelper::map($tags, 'id', 'name'), ['prompt' => '全部粉丝']) ?>
                        <?= $form->field($model, 'send_type')->radioList(['1' => '立即发送','2' => '定时发送']) ?>
                        <?= $form->field($model, 'send_time', [
                            'options' => [
                                'class' => 'hide',
                                'id' => 'send_time'
                            ]
                        ])->widget(DateTimePicker::className(), [
                            'language' => 'zh-CN',
                            'options' => [
                                'value' => date('Y-m-d H:i', strtotime(date('Y-m-d H:i'))),
                            ],
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd hh:ii',
                                'todayHighlight' => true,//今日高亮
                                'autoclose' => true,//选择后自动关闭
                                'todayBtn' => true,//今日按钮显示
                            ]
                        ]);?>

                        <?= $this->render($media_type, [
                            'form' => $form,
                            'model' => $model,
                        ])?>
                    </div>
                    <div class="form-group">
                        <div class="col-sm-12 text-center">
                            <span class="btn btn-primary" onclick="beforSubmit()">保存</span>
                            <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                        </div>
                    </div>　
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $("input[name='SendForm[send_type]']").click(function(){
        var val = $(this).val();

        if (val == 1) {
            $('#send_time').addClass('hide');
        } else {
            $('#send_time').removeClass('hide');
        }
    })
</script>

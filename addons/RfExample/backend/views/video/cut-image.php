<?php
use yii\widgets\ActiveForm;
use common\helpers\AddonUrl;
use common\widgets\webuploader\Images;
use common\widgets\webuploader\Files;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = '截取视频指定帧';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>基本信息</h5>
            </div>
            <div class="ibox-content">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([]); ?>
                    <?= $form->field($model, 'video')->widget(Files::className(), [
                        'config' => [
                            'pick' => [
                                'multiple' => false,
                            ],
                            'accept' => [
                                'mimeTypes' => 'video/*'
                            ]
                        ]
                    ]); ?>
                </div>
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <div class="hr-line-dashed"></div>
                        <button class="btn btn-primary" type="submit">保存</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
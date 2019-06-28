<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\widgets\webuploader\Files;
use kartik\datetime\DateTimePicker;

$this->title = '截取视频指定帧';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body">
                <?= $form->field($model, 'video')->widget(Files::class, [
                    'type' => 'videos',
                    'theme' => 'default',
                    'themeConfig' => [],
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
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
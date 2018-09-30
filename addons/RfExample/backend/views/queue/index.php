<?php
use yii\widgets\ActiveForm;
use common\helpers\AddonUrl;
use common\widgets\webuploader\Images;
use common\widgets\webuploader\Files;
use dosamigos\datetimepicker\DateTimePicker;

$this->title = '消息队列';
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
                    <div class="form-group">
                        <div class="col-sm-12">命令行代码，请在点击保存后查看，默认采用 redis 队列，详细说明请查看内置文档</div>
                        <div class="col-sm-12">监听队列：php yii queue/listen</div>
                        <div class="col-sm-12">执行队列：php yii queue/run</div>
                        <div class="col-sm-12">查看队列：php yii queue/info</div>
                    </div>
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
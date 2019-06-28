<?php
use yii\widgets\ActiveForm;

$this->title = '消息队列';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body">
                <div class="form-group">
                    <div class="col-sm-12">命令行代码，请在点击保存后查看，默认采用 redis 队列，详细说明请查看内置文档</div>
                    <div class="col-sm-12">监听队列：php yii queue/listen</div>
                    <div class="col-sm-12">执行队列：php yii queue/run</div>
                    <div class="col-sm-12">查看队列：php yii queue/info</div>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
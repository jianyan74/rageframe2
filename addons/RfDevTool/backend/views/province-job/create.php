<?php

use yii\widgets\ActiveForm;
use common\helpers\Html;

$this->title = '省市区爬虫创建';
$this->params['breadcrumbs'][] = ['label' => '省市区爬虫', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <?php $form = ActiveForm::begin([]); ?>
            <div class="box-body">
                <div class="col-sm-12">
                    <?= $form->field($model, 'year')->dropDownList($year)->hint('注意：需要开启队列，且手动清空省市区表'); ?>
                    <?= $form->field($model, 'max_level')->dropDownList($maxLevelExplain); ?>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
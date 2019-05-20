<?php

use common\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model addons\AppExample\common\models\CurdSearch */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="curd-search">

    <?php $form = ActiveForm::begin([
        'action' => \common\helpers\Url::to(['index']),
        'method' => 'get',
        'fieldConfig' => [
            'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
        ]
    ]); ?>

    <?= $form->field($model, 'id') ?>
    <?= $form->field($model, 'title') ?>
    <?= $form->field($model, 'cate_id') ?>
    <?= $form->field($model, 'manager_id') ?>
    <?= $form->field($model, 'sort') ?>
    <?php // echo $form->field($model, 'position') ?>
    <?php // echo $form->field($model, 'sex') ?>
    <?php // echo $form->field($model, 'content') ?>
    <?php // echo $form->field($model, 'cover') ?>
    <?php // echo $form->field($model, 'covers') ?>
    <?php // echo $form->field($model, 'attachfile') ?>
    <?php // echo $form->field($model, 'keywords') ?>
    <?php // echo $form->field($model, 'description') ?>
    <?php // echo $form->field($model, 'price') ?>
    <?php // echo $form->field($model, 'views') ?>
    <?php // echo $form->field($model, 'stat_time') ?>
    <?php // echo $form->field($model, 'end_time') ?>
    <?php // echo $form->field($model, 'status') ?>
    <?php // echo $form->field($model, 'email') ?>
    <?php // echo $form->field($model, 'provinces') ?>
    <?php // echo $form->field($model, 'city') ?>
    <?php // echo $form->field($model, 'area') ?>
    <?php // echo $form->field($model, 'ip') ?>
    <?php // echo $form->field($model, 'created_at') ?>
    <?php // echo $form->field($model, 'updated_at') ?>

    <div class="form-group col-lg-offset-3">
        <?= Html::submitButton('搜索', ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton('重置', ['class' => 'btn btn-default']) ?>
    </div>
    <?php ActiveForm::end(); ?>
</div>
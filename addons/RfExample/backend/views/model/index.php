<?php
use common\helpers\Url;
use common\helpers\Html;

use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

$this->title = 'Model案例';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="alert-info alert">
            注意: 这里展示的是在Model下展示比较完美的
        </div>
        <?= Html::linkButton(['view', 'type' => 'time'], '时间', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'date'], '日期', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'datetime'], '日期时间', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'select2'], 'Select2', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'provinces'], '省市区', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'area'], '省市区详细', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModalMax',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'color'], '颜色选择', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'image'], '单图上传', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'images'], '多图上传', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'file'], '单文件上传', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'files'], '多文件上传', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModal',
        ])?>

        <?= Html::linkButton(['view', 'type' => 'multipleInput'], '多Input框', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModalLg',
        ])?>
    </div>
</div>
<br>
<div class="row">
    <div class="col-lg-12">
        <div class="alert-warning alert">
            注意: 这里展示的是在Model下展示多多少少会有点问题，UI体验不是很好，如果你有更好的解决方式欢迎修改后提PR或者在群里反馈
        </div>
        <?= Html::linkButton(['view', 'type' => 'ueditor'], '百度编辑器', [
            'data-toggle' => 'modal',
            'data-target' => '#ajaxModalMax',
        ]) ?>
    </div>
</div>

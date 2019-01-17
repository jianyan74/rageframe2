<?php
use yii\widgets\LinkPager;
use common\helpers\AddonUrl;
use common\helpers\AddonHtmlHelper;

use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

$this->title = 'Curd';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <a href="<?= AddonUrl::to(['export'])?>">导出Excel</a>
                    <?= AddonHtmlHelper::create(['edit']); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <div class="row">
                    <div class="col-sm-12">
                        <?php $form = ActiveForm::begin([
                            'action' => AddonUrl::to(['index']),
                            'method' => 'get',
                        ]); ?>
                        <div class="col-sm-4">
                            <div class="input-group drp-container">
                                <?= DateRangePicker::widget([
                                    'name' => 'queryDate',
                                    'value' => $start_time . '-' . $end_time,
                                    'readonly' => 'readonly',
                                    'useWithAddon' => true,
                                    'convertFormat' => true,
                                    'startAttribute' => 'start_time',
                                    'endAttribute' => 'end_time',
                                    'startInputOptions' => ['value' => $start_time],
                                    'endInputOptions' => ['value' => $end_time],
                                    'pluginOptions' => [
                                        'locale' => ['format' => 'Y-m-d'],
                                    ]
                                ]) . $addon;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group m-b">
                                <input type="text" class="form-control" name="title" placeholder="标题" value="<?= $title ?>"/>
                                <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                            </div>
                        </div>
                        <?php ActiveForm::end(); ?>
                    </div>
                </div>
                <table class="table table-hover">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>标题</th>
                        <th>排序</th>
                        <th>开始结束时间</th>
                        <th>操作</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr id = <?= $model->id; ?>>
                            <td><?= $model->id; ?></td>
                            <td><?= $model->title; ?></td>
                            <td class="col-md-1"><?= AddonHtmlHelper::sort($model['sort']); ?></td>
                            <td>
                                开始：<?= Yii::$app->formatter->asDatetime($model->start_time); ?> <br>
                                结束：<?= Yii::$app->formatter->asDatetime($model->end_time); ?>
                            </td>
                            <td>
                                <?= AddonHtmlHelper::edit(['edit','id' => $model->id]); ?>
                                <?= AddonHtmlHelper::status($model['status']); ?>
                                <?= AddonHtmlHelper::delete(['delete','id' => $model->id]); ?>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <div class="box-footer">
                <?= LinkPager::widget([
                    'pagination' => $pages
                ]);?>
            </div>
        </div>
    </div>
</div>
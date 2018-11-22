<?php

use yii\widgets\LinkPager;
use common\helpers\AddonUrl;

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
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= $this->title; ?></h5>
                <div class="ibox-tools">
                    <a href="<?= AddonUrl::to(['export'])?>">导出Excel</a>
                    <a class="btn btn-primary btn-xs" href="<?= AddonUrl::to(['edit'])?>">
                        <i class="fa fa-plus"></i>  创建
                    </a>
                </div>
            </div>
            <div class="ibox-content">
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
                            <td class="col-md-1"><input type="text" class="form-control" value="<?= $model['sort']?>" onblur="rfSort(this)"></td>
                            <td>
                                开始：<?= Yii::$app->formatter->asDatetime($model->start_time); ?> <br>
                                结束：<?= Yii::$app->formatter->asDatetime($model->end_time); ?>
                            </td>
                            <td>
                                <a href="<?= AddonUrl::to(['edit','id' => $model->id])?>"><span class="btn btn-info btn-sm">编辑</span></a>&nbsp
                                <?= \common\helpers\HtmlHelper::statusSpan($model['status']);?>
                                <a href="<?= AddonUrl::to(['delete','id'=> $model->id])?>" onclick="rfDelete(this);return false;"><span class="btn btn-warning btn-sm">删除</span></a>&nbsp
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <div class="row">
                    <div class="col-sm-12">
                        <?= LinkPager::widget([
                            'pagination' => $pages,
                            'maxButtonCount' => 5,
                            'firstPageLabel' => "首页",
                            'lastPageLabel' => "尾页",
                            'nextPageLabel' => "下一页",
                            'prevPageLabel' => "上一页",
                        ]);?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
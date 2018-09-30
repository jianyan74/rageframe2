<?php

use yii\widgets\LinkPager;
use kartik\daterange\DateRangePicker;
use common\helpers\AddonUrl;
use yii\widgets\ActiveForm;

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

$this->title = '默认搜索';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5><?= $this->title; ?></h5>
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
                                    'value' => $searchModel->stat_time . '-' . $searchModel->end_time,
                                    'readonly' => 'readonly',
                                    'useWithAddon' => true,
                                    'convertFormat' => true,
                                    'startAttribute' => 'stat_time',
                                    'endAttribute' => 'end_time',
                                    'startInputOptions' => ['value' => $searchModel->stat_time],
                                    'endInputOptions' => ['value' => $searchModel->end_time],
                                    'pluginOptions' => [
                                        'locale' => ['format' => 'Y-m-d'],
                                    ]
                                ]) . $addon;?>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group m-b">
                                <input type="text" class="form-control" name="title" placeholder="标题" value="<?= $searchModel->title ?>"/>
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
                        <th>创建时间</th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($models as $model){ ?>
                        <tr id = <?= $model->id; ?>>
                            <td><?= $model->id; ?></td>
                            <td><?= $model->title; ?></td>
                            <td class="col-md-1"><input type="text" class="form-control" value="<?= $model['sort']?>" onblur="rfSort(this)"></td>
                            <td><?= Yii::$app->formatter->asDatetime($model->created_at); ?></td>
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
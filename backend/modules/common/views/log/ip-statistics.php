<?php

use yii\grid\GridView;
use common\helpers\Url;
use yii\widgets\LinkPager;
use common\helpers\DebrisHelper;
use kartik\daterange\DateRangePicker;
use yii\widgets\ActiveForm;

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

$this->title = 'IP统计';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['ip-statistics']];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['index']) ?>"> 全局日志</a></li>
                <li class="active"><a href="<?= Url::to(['ip-statistics']) ?>"> IP统计</a></li>
                <li><a href="<?= Url::to(['statistics']) ?>"> 数据统计</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <div class="box-body table-responsive">
                        <div class="row">
                            <div class="col-sm-12">
                                <?php $form = ActiveForm::begin([
                                    'action' => Url::to(['ip-statistics']),
                                    'method' => 'get',
                                ]); ?>
                                <div class="col-sm-3">
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
                                <div class="col-sm-3">
                                    <div class="input-group m-b">
                                        <input type="text" class="form-control" name="ip" placeholder="ip码(int)" value="<?= $ip ?>"/>
                                        <span class="input-group-btn"><button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button></span>
                                    </div>
                                </div>
                                <?php ActiveForm::end(); ?>
                            </div>
                        </div>
                        <table class="table table-hover">
                            <thead>
                            <tr>
                                <th>请求数量</th>
                                <th>IP</th>
                                <th>IP码(int)</th>
                                <th>所在地区</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php foreach($models as $model){ ?>
                                <tr>
                                    <td><?= $model['count']; ?></td>
                                    <td><?= DebrisHelper::long2ip($model['ip']); ?></td>
                                    <td><?= $model['ip']; ?></td>
                                    <td><?= DebrisHelper::analysisIp($model['ip']); ?></td>
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
    </div>
</div>



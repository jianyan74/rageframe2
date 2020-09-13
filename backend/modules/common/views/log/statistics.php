<?php

use yii\grid\GridView;
use common\enums\AppEnum;
use common\helpers\Html;
use common\helpers\Url;
use common\helpers\DebrisHelper;

$this->title = '数据统计';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['statistics']];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['index']) ?>"> 全局日志</a></li>
                <li><a href="<?= Url::to(['ip-statistics']) ?>"> IP统计</a></li>
                <li class="active"><a href="<?= Url::to(['statistics']) ?>"> 数据统计</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">流量状态分析</h3>
                        </div>
                        <div class="box-body">
                            <?= \common\widgets\echarts\Echarts::widget([
                                'config' => [
                                    'server' => Url::to(['flow-stat']),
                                    'height' => '400px',
                                ],
                                'themeConfig' => [
                                    'today' => '今日',
                                    'yesterday' => '昨日',
                                    'thisWeek' => '本周',
                                    'thisMonth' => '本月',
                                    'thisYear' => '本年',
                                    'lastYear' => '去年',
                                ],
                            ]) ?>
                        </div>
                    </div>
                    <div class="box">
                        <div class="box-header">
                            <h3 class="box-title">异常状态分析</h3>
                        </div>
                        <div class="box-body">
                            <?= \common\widgets\echarts\Echarts::widget([
                                'config' => [
                                    'server' => Url::to(['stat']),
                                    'height' => '400px',
                                ],
                                'themeConfig' => [
                                    'today' => '今日',
                                    'yesterday' => '昨日',
                                    'thisWeek' => '本周',
                                    'thisMonth' => '本月',
                                    'thisYear' => '本年',
                                    'lastYear' => '去年',
                                ],
                            ]) ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
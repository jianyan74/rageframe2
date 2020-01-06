<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\daterange\DateRangePicker;

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

$this->title = '粉丝关注统计';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?= Html::jsFile('@web/resources/plugins/echarts/echarts-all.js') ?>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i
                            class="ion ion-stats-bars green"></i> <?= $today['new_attention']; ?></span>
                <span class="info-box-text">今日新关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i
                            class="icon ion-arrow-graph-down-right red"></i> <?= $today['cancel_attention']; ?></span>
                <span class="info-box-text">今日取消关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i
                            class="icon ion-arrow-graph-up-right green"></i> <?= $today['increase_attention']; ?></span>
                <span class="info-box-text">今日净增关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><?= $today['cumulate_attention']; ?></span>
                <span class="info-box-text">累积关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i
                            class="ion ion-stats-bars green"></i> <?= $yesterday['new_attention']; ?></span>
                <span class="info-box-text">昨日新关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i
                            class="icon ion-arrow-graph-down-right red"></i> <?= $yesterday['cancel_attention']; ?></span>
                <span class="info-box-text">昨日取消关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><i
                            class="icon ion-arrow-graph-up-right green"></i> <?= $yesterday['increase_attention']; ?></span>
                <span class="info-box-text">昨日净增关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
    <div class="col-md-3 col-sm-6 col-xs-12">
        <div class="info-box">
            <div class="info-box-content p-md">
                <span class="info-box-number"><?= $yesterday['cumulate_attention']; ?></span>
                <span class="info-box-text">昨日累积关注(人)</span>
            </div>
            <!-- /.info-box-content -->
        </div>
        <!-- /.info-box -->
    </div>
    <!-- /.col -->
</div>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <div class="box-body table-responsive">
                <div class="col-sm-12 normalPaddingJustV">
                    <?php $form = ActiveForm::begin([
                        'action' => Url::to(['fans-follow']),
                        'method' => 'get'
                    ]); ?>
                    <div class="row">
                        <div class="col-sm-4 p-r-no-away">
                            <div class="input-group drp-container">
                                <?= DateRangePicker::widget([
                                    'name' => 'queryDate',
                                    'value' => $from_date . '-' . $to_date,
                                    'readonly' => 'readonly',
                                    'useWithAddon' => true,
                                    'convertFormat' => true,
                                    'startAttribute' => 'from_date',
                                    'endAttribute' => 'to_date',
                                    'startInputOptions' => ['value' => $from_date],
                                    'endInputOptions' => ['value' => $to_date],
                                    'pluginOptions' => [
                                        'locale' => ['format' => 'Y-m-d'],
                                    ]
                                ]) . $addon; ?>
                            </div>
                        </div>
                        <div class="col-sm-3 p-l-no-away">
                            <div class="input-group m-b">
                                <?= Html::tag('span',
                                    '<button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>',
                                    ['class' => 'input-group-btn']) ?>
                            </div>
                        </div>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
                <table class="table">
                    <tr>
                        <td colspan="4">
                            <div id="main" style="width: 100%;height:400px;"></div>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('main'));
    var new_attention = <?= json_encode($fansStat['new_attention'])?>;
    var cancel_attention = <?= json_encode($fansStat['cancel_attention'])?>;
    var increase_attention = <?= json_encode($fansStat['increase_attention'])?>;
    var cumulate_attention = <?= json_encode($fansStat['cumulate_attention'])?>;
    var chartTime = <?= json_encode($fansStat['chartTime'])?>;

    function chartOption() {
        var option = {
            title: {
                subtext: '人数'
            },
            tooltip: {
                trigger: 'axis'
            },
            legend: {
                data: ['新关注', '取消关注', '净增关注', '累积关注'],
                selected: {
                    '新关注': true,
                    '取消关注': true,
                    '净增关注': true,
                    '累积关注': false
                }
            },
            toolbox: {
                show: false,
                feature: {
                    mark: {show: true},
                    dataView: {show: true, readOnly: false},
                    magicType: {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                    restore: {show: true},
                    saveAsImage: {show: true}
                }
            },
            calculable: true,
            xAxis: [
                {
                    type: 'category',
                    boundaryGap: false,
                    data: chartTime
                }
            ],
            yAxis: [
                {
                    type: 'value'
                }
            ],
            series: [
                {
                    name: '新关注',
                    type: 'line',
                    smooth: true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data: new_attention
                },
                {
                    name: '取消关注',
                    type: 'line',
                    smooth: true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data: cancel_attention
                },
                {
                    name: '净增关注',
                    type: 'line',
                    smooth: true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data: increase_attention
                },
                {
                    name: '累积关注',
                    type: 'line',
                    smooth: true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data: cumulate_attention
                }
            ]
        };

        return option;
    }

    myChart.setOption(chartOption()); // 加载图表
</script>
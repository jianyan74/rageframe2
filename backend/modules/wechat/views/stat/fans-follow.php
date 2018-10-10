<?php
use yii\helpers\Url;
use yii\helpers\Html;
use kartik\daterange\DateRangePicker;

$addon = <<< HTML
<span class="input-group-addon">
    <i class="glyphicon glyphicon-calendar"></i>
</span>
HTML;

$this->title = '粉丝关注统计';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<?= Html::jsFile('/backend/resources/js/plugins/echarts/echarts-all.js')?>

<div class="wrapper wrapper-content animated fadeInRight">
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h4>今日新关注</h4>
                    <h1 class="no-margins"><?= $today['new_attention']; ?></h1>
                    <small>人</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h4>今日取消关注</h4>
                    <h1 class="no-margins"><?= $today['cancel_attention']; ?></h1>
                    <small>人</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h4>今日净增关注</h4>
                    <h1 class="no-margins"><?= $today['increase_attention']; ?></h1>
                    <small>人</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h4>累积关注</h4>
                    <h1 class="no-margins"><?= $today['cumulate_attention']; ?></h1>
                    <small>人</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h4>昨日新关注</h4>
                    <h1 class="no-margins"><?= $yesterday['new_attention']; ?></h1>
                    <small>人</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h4>昨日取消关注</h4>
                    <h1 class="no-margins"><?= $yesterday['cancel_attention']; ?></h1>
                    <small>人</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h4>昨日净增关注</h4>
                    <h1 class="no-margins"><?= $yesterday['increase_attention']; ?></h1>
                    <small>人</small>
                </div>
            </div>
        </div>
        <div class="col-sm-3">
            <div class="ibox">
                <div class="ibox-content">
                    <h4>累积关注</h4>
                    <h1 class="no-margins"><?= $yesterday['cumulate_attention']; ?></h1>
                    <small>人</small>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-12">
            <div class="ibox float-e-margins">
                <div class="ibox-title">
                    <h5><i class="fa fa-cog"></i>  关键指标详解</h5>
                </div>
                <div class="ibox-content">
                    <div class="col-sm-12">
                        <form action="" method="get" class="form-horizontal" role="form" id="form">
                            <div class="row">
                                <div class="col-sm-4">
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
                                            ]) . $addon;?>
                                    </div>
                                </div>
                                <div class="col-sm-3">
                                    <div class="input-group m-b">
                                    <span class="input-group-btn">
                                         <button class="btn btn-white"><i class="fa fa-search"></i> 搜索</button>
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </form>
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
            title : {
                subtext: '人数'
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data:['新关注','取消关注','净增关注','累积关注'],
                selected: {
                    '新关注' : true,
                    '取消关注' : true,
                    '净增关注' : true,
                    '累积关注' : false
                }
            },
            toolbox: {
                show : false,
                feature : {
                    mark : {show: true},
                    dataView : {show: true, readOnly: false},
                    magicType : {show: true, type: ['line', 'bar', 'stack', 'tiled']},
                    restore : {show: true},
                    saveAsImage : {show: true}
                }
            },
            calculable : true,
            xAxis : [
                {
                    type : 'category',
                    boundaryGap : false,
                    data : chartTime
                }
            ],
            yAxis : [
                {
                    type : 'value'
                }
            ],
            series : [
                {
                    name:'新关注',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data:new_attention
                },
                {
                    name:'取消关注',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data:cancel_attention
                },
                {
                    name:'净增关注',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data:increase_attention
                },
                {
                    name:'累积关注',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data:cumulate_attention
                }
            ]
        };

        return option;
    }

    myChart.setOption(chartOption()); // 加载图表

    function getServerInfo(){
        $.ajax({
            type : "get",
            url  : "<?= Url::to(['server'])?>",
            dataType : "json",
            data: {},
            success: function(data){
                if(data.code == 200) {
                    var html = template('model',data.data);
                    $('#sys-hardware').html(html);

                    var netWork = data.data.netWork;
                    $('#netWork_allOutSpeed').text(netWork.allOutSpeed);
                    $('#netWork_allInputSpeed').text(netWork.allInputSpeed);
                    $('#netWork_currentOutSpeed').text(netWork.currentOutSpeed + ' KB/s');
                    $('#netWork_currentInputSpeed').text(netWork.currentInputSpeed + ' KB/s');

                    currentOutSpeed.shift();
                    currentInputSpeed.shift();
                    currentOutSpeed.push(netWork.currentOutSpeed);
                    currentInputSpeed.push(netWork.currentInputSpeed);
                    chartTime = data.data.chartTime;
                    myChart.setOption(chartOption()); // 加载图表
                }else{
                    alert(data.msg);
                }
            }
        });
    }
</script>
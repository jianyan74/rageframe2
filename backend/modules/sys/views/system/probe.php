<?php
use common\helpers\Url;
use common\helpers\Html;

$this->title = '系统探针';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?= Html::jsFile('@web/resources/plugins/echarts/echarts-all.js')?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-cog"></i> 服务器参数</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table">
                    <tr>
                        <td>服务器域名地址</td>
                        <td><?= $info['environment']['domainIP'] ?></td>
                        <td>服务器标识</td>
                        <td><?= $info['environment']['flag'] ?></td>
                    </tr>
                    <tr>
                        <td>操作系统</td>
                        <td><?= $info['environment']['os'] ?></td>
                        <td>服务器解析引擎</td>
                        <td><?= $info['environment']['webEngine'] ?></td>
                    </tr>
                    <tr>
                        <td>服务器语言</td>
                        <td><?= $info['environment']['language'] ?></td>
                        <td>服务器端口</td>
                        <td><?= $info['environment']['webPort'] ?></td>
                    </tr>
                    <tr>
                        <td>服务器主机名</td>
                        <td><?= $info['environment']['name'] ?></td>
                        <td>站点绝对路径</td>
                        <td><?= $info['environment']['webPath'] ?></td>
                    </tr>
                    <tr>
                        <td>服务器当前时间</td>
                        <td><span id="divTime"></span></td>
                        <td>服务器已运行时间</td>
                        <td><?= $info['uptime'] ?></td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-cog"></i> 服务器硬件数据</h3>
            </div>
            <div class="box-body table-responsive">
                <div id="schedule" style="width: 100%;height:150px;"></div>
                <div class="col-sm-12 text-center" id="memData">
                    <div class="col-sm-3 "><?= $info['hardDisk']['used']?>/<?= $info['hardDisk']['total']?> (G)</div>
                    <div class="col-sm-3"><?= $info['memory']['real']['used'] ?>/<?= $info['memory']['memory']['total']; ?> (M)</div>
                    <div class="col-sm-3"><?= $info['memory']['memory']['used'] ?>/<?= $info['memory']['memory']['total']; ?> (M)</div>
                    <div class="col-sm-3"><?= $info['memory']['cache']['real']?>/<?= $info['memory']['cache']['total']?> (M)<br>Buffers缓冲为 <?= $info['memory']['memory']['buffers']?> M</div>
                </div>
                <div id="sys-hardware">
                    <table class="table">
                        <tr>
                            <td>CPU</td>
                            <td><?= $info['cpu']['num'] ?></td>
                            <td>CPU型号</td>
                            <td><?= $info['cpu']['model'] ?></td>
                        </tr>
                        <tr>
                            <td>CPU使用情况</td>
                            <td colspan="3">
                                <?= $info['cpuUse']['explain'] ?>
                            </td>
                        </tr>
                        <tr>
                            <td>系统平均负载(1分钟、5分钟、以及15分钟的负载均值)</td>
                            <td colspan="3"><?= $info['loadavg']['explain'] ?></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><i class="fa fa-cog"></i> 服务器实时网络数据</h3>
            </div>
            <div class="box-body table-responsive">
                <table class="table">
                    <tr>
                        <td>总发送</td>
                        <td id="netWork_allOutSpeed"><?= $info['netWork']['allOutSpeed'] ?></td>
                        <td>总接收</td>
                        <td id="netWork_allInputSpeed"><?= $info['netWork']['allInputSpeed'] ?></td>
                    </tr>
                    <tr>
                        <td>发送速度</td>
                        <td id="netWork_currentOutSpeed"><?= $info['netWork']['currentOutSpeed'] ?></td>
                        <td>接收速度</td>
                        <td id="netWork_currentInputSpeed"><?= $info['netWork']['currentInputSpeed'] ?></td>
                    </tr>
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

<script type="text/html" id="model">
    <table class="table">
        <tr>
            <td>CPU</td>
            <td>{{cpu.num}}</td>
            <td>CPU型号</td>
            <td>{{cpu.model}}</td>
        </tr>
        <tr>
            <td>CPU使用情况</td>
            <td colspan="3">
                {{#cpuUse.explain}}
            </td>
        </tr>
        <tr>
            <td>系统平均负载(1分钟、5分钟、以及15分钟的负载均值)</td>
            <td colspan="3">{{loadavg.explain}}</td>
        </tr>
    </table>
</script>

<script type="text/html" id="mem">
    <div class="col-sm-3 ">{{hardDisk.used}}/{{hardDisk.total}} (G)</div>
    <div class="col-sm-3">{{memory.real.used}}/{{memory.memory.total}} (M)</div>
    <div class="col-sm-3">{{memory.memory.used}}/{{memory.memory.total}} (M)</div>
    <div class="col-sm-3">{{memory.cache.real}}/{{memory.cache.total}} (M)<br>Buffers缓冲为 {{memory.memory.buffers}} M</div>
</script>

<script>
    var schedule = echarts.init(document.getElementById('schedule'));

    var hdSpeed = [100, 0];
    var memTotal = [100, 0];
    var memCached = [100, 0];
    var memRealUsed = [100, 0];

    function scheduleOption() {
        var labelTop = {
            normal : {
                label : {
                    show : true,
                    position : 'center',
                    formatter : '{b}',
                    textStyle: {
                        baseline : 'bottom'
                    },
                },
                labelLine : {
                    show : false
                }
            }
        };

        var labelFromatter = {
            normal : {
                label : {
                    formatter : function (a,b,c){
                        return 100 - c + '%'
                    },
                    textStyle: {
                        baseline : 'top'
                    }
                }
            },
        }

        var labelBottom = {
            normal : {
                color: '#ccc',
                label : {
                    show : true,
                    position : 'center'
                },
                labelLine : {
                    show : false
                }
            },
            emphasis: {
                color: 'rgba(0,0,0,0)'
            }
        };
        var radius = [55, 65];
        var optionData = {
            series : [
                {
                    type : 'pie',
                    center : ['13%', '50%'],
                    radius : radius,
                    x: '0%', // for funnel
                    itemStyle : labelFromatter,
                    data : [
                        {name:'other', value: hdSpeed[0], itemStyle : labelBottom},
                        {name:'硬盘使用率', value:hdSpeed[1],itemStyle : labelTop}
                    ]
                },
                {
                    type : 'pie',
                    center : ['38%', '50%'],
                    radius : radius,
                    x:'60%', // for funnel
                    itemStyle : labelFromatter,
                    data : [
                        {name:'other', value:memRealUsed[0], itemStyle : labelBottom},
                        {name:'真实内存使用率', value:memRealUsed[1],itemStyle : labelTop}
                    ]
                },
                {
                    type : 'pie',
                    center : ['62%', '50%'],
                    radius : radius,
                    x:'20%', // for funnel
                    itemStyle : labelFromatter,
                    data : [
                        {name:'other', value:memTotal[0], itemStyle : labelBottom},
                        {name:'物理内存使用率', value:memTotal[1],itemStyle : labelTop}
                    ]
                },
                {
                    type : 'pie',
                    center : ['87%', '50%'],
                    radius : radius,
                    x:'40%', // for funnel
                    itemStyle : labelFromatter,
                    data : [
                        {name:'other', value:memCached[0], itemStyle : labelBottom},
                        {name:'Cache化内存使用率', value:memCached[1],itemStyle : labelTop}
                    ]
                }
            ]
        };

        return optionData;
    }

    schedule.setOption(scheduleOption()); // 加载图表
</script>

<script type="text/javascript">
    var myChart = echarts.init(document.getElementById('main'));
    var currentOutSpeed = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    var currentInputSpeed = [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0];
    var chartTime = <?= json_encode($info['chartTime'])?>;

    function chartOption() {
        var option = {
            title : {
                subtext: '单位 KB/s'
            },
            tooltip : {
                trigger: 'axis'
            },
            legend: {
                data:['发送速度','接收速度']
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
                    name:'发送速度',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data:currentOutSpeed
                },
                {
                    name:'接收速度',
                    type:'line',
                    smooth:true,
                    itemStyle: {normal: {areaStyle: {type: 'default'}}},
                    data:currentInputSpeed
                }
            ]
        };

        return option;
    }

    myChart.setOption(chartOption()); // 加载图表

    $(document).ready(function(){
        setTime();
        setInterval(setTime, 1000);
        setInterval(getServerInfo, 3000);
    });

    function setTime() {
        var d = new Date(), str = '';
        str += d.getFullYear() + ' 年 '; // 获取当前年份
        str += d.getMonth() + 1 + ' 月 '; // 获取当前月份（0——11）
        str += d.getDate() + ' 日  ';
        str += d.getHours() + ' 时 ';
        str += d.getMinutes() + ' 分 ';
        str += d.getSeconds() + ' 秒 ';
        $("#divTime").text(str);
    }

    function getServerInfo() {
        $.ajax({
            type : "get",
            url  : "<?= Url::to(['probe'])?>",
            dataType : "json",
            data: {},
            success: function(data) {
                if (data.code == 200) {
                    var data = data.data;
                    var html = template('model',data);
                    $('#sys-hardware').html(html);
                    var html2 = template('mem',data);
                    $('#memData').html(html2);

                    var netWork = data.netWork;
                    $('#netWork_allOutSpeed').text(netWork.allOutSpeed + ' G');
                    $('#netWork_allInputSpeed').text(netWork.allInputSpeed + ' G');
                    $('#netWork_currentOutSpeed').text(netWork.currentOutSpeed + ' KB/s');
                    $('#netWork_currentInputSpeed').text(netWork.currentInputSpeed + ' KB/s');

                    currentOutSpeed.shift();
                    currentInputSpeed.shift();
                    currentOutSpeed.push(netWork.currentOutSpeed);
                    currentInputSpeed.push(netWork.currentInputSpeed);
                    chartTime = data.chartTime;
                    myChart.setOption(chartOption()); // 加载图表

                    //内存
                    var memory = data.memory;
                    var memPercent = memory.memory.usage_rate;
                    var memCachedPercent = memory.cache.usage_rate;
                    var memRealPercent = memory.real.usage_rate;
                    var hardDiskUsageRate = data.hardDisk.usage_rate;

                    memPercent = memPercent.toFixed(0);
                    memCachedPercent = memCachedPercent.toFixed(0);
                    memRealPercent = memRealPercent.toFixed(0);
                    hardDiskUsageRate = hardDiskUsageRate.toFixed(0);

                    hdSpeed = [100 - hardDiskUsageRate, hardDiskUsageRate];
                    memTotal = [100 - memPercent, memPercent];
                    memCached = [100 - memCachedPercent, memCachedPercent];
                    memRealUsed = [100 - memRealPercent, memRealPercent];
                    schedule.setOption(scheduleOption()); // 加载图表
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }
</script>
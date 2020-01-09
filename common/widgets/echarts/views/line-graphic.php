<?php

echo $this->render("_nav", [
    'boxId' => $boxId,
    'config' => $config,
    'themeJs' => $themeJs,
    'themeConfig' => $themeConfig,
]);

$jsonConfig = \yii\helpers\Json::encode($config);

Yii::$app->view->registerJs(<<<JS

var boxId = "$boxId";
    echartsList[boxId] = echarts.init(document.getElementById(boxId + '-echarts'), "$themeJs");
    echartsListConfig[boxId] = jQuery.parseJSON('$jsonConfig');
        
    // 动态加载数据
    $('#'+ boxId +' div span').click(function () {
        $(this).parent().find('span').removeClass('orange');
        $(this).addClass('orange');
        var type = $(this).data('type');
        var start = $(this).attr('data-start');
        var end = $(this).attr('data-end');
        var boxId = $(this).parent().parent().attr('id');
        var config = echartsListConfig[boxId];
        
        $.ajax({
            type:"get",
            url: config.server,
            dataType: "json",
            data: {type:type, echarts_type: 'line-graphic', echarts_start: start, echarts_end: end},
            success: function(result){
                var data = result.data;
                if (parseInt(result.code) === 200) {
                     echartsList[boxId].setOption({
                            color: ['#8EC9EB'],
                            legend: {
                                // data:['高度(km)与气温(°C)变化关系']
                            },
                            tooltip: {
                                trigger: 'axis',
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis: {
                                type: 'value',
                                splitLine: {
                                    show: false
                                },
                                axisLabel: {
                                    formatter: '{value}'
                                }
                            },
                            yAxis: {
                                type: 'category',
                                axisLine: {onZero: false},
                                axisLabel: {
                                    formatter: '{value}'
                                },
                                boundaryGap: true,
                                data: data.fieldsName
                            },
                            graphic: [
                                {
                                    type: 'image',
                                    id: 'logo',
                                    right: 20,
                                    top: 20,
                                    z: -10,
                                    bounding: 'raw',
                                    origin: [75, 75]
                                }
                            ],
                            series: data.seriesData
                        }, true);
                } else {
                    rfWarning(result.message);
                }
            }
        });
    });

    // 首个触发点击
    $('#'+ boxId +' div span:first').trigger('click');
JS
) ?>
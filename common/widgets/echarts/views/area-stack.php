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
            data: {type:type, echarts_type: 'area-stack', echarts_start: start, echarts_end: end},
            success: function(result){
                var data = result.data;
                if (parseInt(result.code) === 200) {
                     var seriesData = data.seriesData;
                     for(let j = 0; j < seriesData.length; j++) {
                         seriesData[j]['stack'] = '总数';
                         seriesData[j]['areaStyle'] = [];
                         seriesData[j]['areaStyle']['normal'] = {};
                     }
                    
                    echartsList[boxId].setOption({
                            title: {
                                text: ''
                            },
                            tooltip : {
                                trigger: 'axis',
                                axisPointer: {
                                    type: 'cross',
                                    label: {
                                        backgroundColor: '#6a7985'
                                    }
                                }
                            },
                            legend: {
                                data: data.fieldsName
                            },
                            grid: {
                                left: '3%',
                                right: '4%',
                                bottom: '3%',
                                containLabel: true
                            },
                            xAxis : [
                                {
                                    type : 'category',
                                    boundaryGap : false,
                                    data : data.xAxisData
                                }
                            ],
                            yAxis : [
                                {
                                    type : 'value'
                                }
                            ],
                            series : data.seriesData,
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
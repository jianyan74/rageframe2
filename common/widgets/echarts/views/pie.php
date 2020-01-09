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
    echartsList[boxId] = echarts.init(document.getElementById(boxId + '-echarts'), '$themeJs');
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
            data: {type:type, echarts_type: 'pie', echarts_start: start, echarts_end: end},
            success: function(result){
                var data = result.data;
                if (parseInt(result.code) === 200) {
                     echartsList[boxId].setOption({
                        title : {
                            text: '',
                            subtext: '',
                            x:'center'
                        },
                        legend: {
                            orient: 'vertical',
                            left: 'right',
                            show: true,
                            data: data.fieldsName
                        },
                       tooltip : {
                            trigger: 'item',
                            formatter: "{a} <br/>{b} : {c} ({d}%)"
                        },
                        calculable : true,
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
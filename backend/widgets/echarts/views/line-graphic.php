<?php

echo $this->render("_nav", [
    'boxId' => $boxId,
    'config' => $config,
    'themeJs' => $themeJs,
    'themeConfig' => $themeConfig,
]);

$jsonConfig = \yii\helpers\Json::encode($config);

Yii::$app->view->registerJs(<<<JS
    option = {
    color: ['#8EC9EB'],
    legend: {
        data:['高度(km)与气温(°C)变化关系']
    },
    tooltip: {
        trigger: 'axis',
        formatter: "Temperature : <br/>{b}km : {c}°C"
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
            formatter: '{value} °C'
        }
    },
    yAxis: {
        type: 'category',
        axisLine: {onZero: false},
        axisLabel: {
            formatter: '{value} km'
        },
        boundaryGap: true,
        data: ['0', '10', '20', '30', '40', '50', '60', '70', '80']
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
    series: [
        {
            name: '高度(km)与气温(°C)变化关系',
            type: 'bar',
            smooth: true,
            barCategoryGap: 25,
            lineStyle: {
                normal: {
                    width: 3,
                    shadowColor: 'rgba(0,0,0,0.4)',
                    shadowBlur: 10,
                    shadowOffsetY: 10
                }
            },
            data:[15, 50, 56.5, 46.5, 22.1, 2.5, 27.7, 55.7, 76.5]
        }
    ]
};

var rotation = 0;
setInterval(function () {
    myChart.setOption({
        graphic: {
            id: 'logo',
            rotation: (rotation += Math.PI / 360) % (Math.PI * 2)
        }
    });
}, 30);
JS
) ?>
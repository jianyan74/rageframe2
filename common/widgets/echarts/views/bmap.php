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
    var geoCoordMap;
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
            data: {type:type, echarts_type: 'bmap', echarts_start: start, echarts_end: end},
            success: function(result){
                var data = result.data;
                if (parseInt(result.code) === 200) {
                     geoCoordMap = data.geoCoordMapData;
                    echartsList[boxId].setOption({
                            title: {
                                text: '',
                                subtext: '',
                                sublink: '',
                                left: 'center'
                            },
                            tooltip : {
                                trigger: 'item'
                            },
                            bmap: {
                                center: [104.114129, 37.550339],
                                zoom: 5,
                                roam: true,
                                mapStyle: {
                                    styleJson: [{
                                        'featureType': 'water',
                                        'elementType': 'all',
                                        'stylers': {
                                            'color': '#d1d1d1'
                                        }
                                    }, {
                                        'featureType': 'land',
                                        'elementType': 'all',
                                        'stylers': {
                                            'color': '#f3f3f3'
                                        }
                                    }, {
                                        'featureType': 'railway',
                                        'elementType': 'all',
                                        'stylers': {
                                            'visibility': 'off'
                                        }
                                    }, {
                                        'featureType': 'highway',
                                        'elementType': 'all',
                                        'stylers': {
                                            'color': '#fdfdfd'
                                        }
                                    }, {
                                        'featureType': 'highway',
                                        'elementType': 'labels',
                                        'stylers': {
                                            'visibility': 'off'
                                        }
                                    }, {
                                        'featureType': 'arterial',
                                        'elementType': 'geometry',
                                        'stylers': {
                                            'color': '#fefefe'
                                        }
                                    }, {
                                        'featureType': 'arterial',
                                        'elementType': 'geometry.fill',
                                        'stylers': {
                                            'color': '#fefefe'
                                        }
                                    }, {
                                        'featureType': 'poi',
                                        'elementType': 'all',
                                        'stylers': {
                                            'visibility': 'off'
                                        }
                                    }, {
                                        'featureType': 'green',
                                        'elementType': 'all',
                                        'stylers': {
                                            'visibility': 'off'
                                        }
                                    }, {
                                        'featureType': 'subway',
                                        'elementType': 'all',
                                        'stylers': {
                                            'visibility': 'off'
                                        }
                                    }, {
                                        'featureType': 'manmade',
                                        'elementType': 'all',
                                        'stylers': {
                                            'color': '#d1d1d1'
                                        }
                                    }, {
                                        'featureType': 'local',
                                        'elementType': 'all',
                                        'stylers': {
                                            'color': '#d1d1d1'
                                        }
                                    }, {
                                        'featureType': 'arterial',
                                        'elementType': 'labels',
                                        'stylers': {
                                            'visibility': 'off'
                                        }
                                    }, {
                                        'featureType': 'boundary',
                                        'elementType': 'all',
                                        'stylers': {
                                            'color': '#fefefe'
                                        }
                                    }, {
                                        'featureType': 'building',
                                        'elementType': 'all',
                                        'stylers': {
                                            'color': '#d1d1d1'
                                        }
                                    }, {
                                        'featureType': 'label',
                                        'elementType': 'labels.text.fill',
                                        'stylers': {
                                            'color': '#999999'
                                        }
                                    }]
                                }
                            },
                            series : [
                                {
                                    name: '',
                                    type: 'scatter',
                                    coordinateSystem: 'bmap',
                                    data: convertData(data.seriesData),
                                    symbolSize: function (val) {
                                        return val[2] / 10;
                                    },
                                    label: {
                                        normal: {
                                            formatter: '{b}',
                                            position: 'right',
                                            show: false
                                        },
                                        emphasis: {
                                            show: true
                                        }
                                    },
                                    itemStyle: {
                                        normal: {
                                            color: 'rgb(89, 196, 230)'
                                        }
                                    }
                                },
                                {
                                    name: '',
                                    type: 'effectScatter',
                                    coordinateSystem: 'bmap',
                                    data: convertData(data.seriesData.sort(function (a, b) {
                                        return b.value - a.value;
                                    }).slice(0, 6)),
                                    symbolSize: function (val) {
                                        return val[2] / 10;
                                    },
                                    showEffectOn: 'render',
                                    rippleEffect: {
                                        brushType: 'stroke'
                                    },
                                    hoverAnimation: true,
                                    label: {
                                        normal: {
                                            formatter: '{b}',
                                            position: 'right',
                                            show: true
                                        }
                                    },
                                    itemStyle: {
                                        normal: {
                                            color: 'rgb(89, 196, 230)',
                                            shadowBlur: 10,
                                            shadowColor: 'rgb(150, 222, 232)'
                                        }
                                    },
                                    zlevel: 1
                                }
                            ]
                        }, true);
                } else {
                    rfWarning(result.message);
                }
            }
        });
    });
    
    var convertData = function (data) {
        var res = [];
        for (var i = 0; i < data.length; i++) {
            var geoCoord = geoCoordMap[data[i].name];
            if (geoCoord) {
                res.push({
                    name: data[i].name,
                    value: geoCoord.concat(data[i].value)
                });
            }
        }
        return res;
    };

    // 首个触发点击
    $('#'+ boxId +' div span:first').trigger('click');
JS
) ?>
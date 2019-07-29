<?php

use common\helpers\Html;

?>

    <div class="box-body" id="<?= $boxId; ?>">
        <div>
            <?php $i = 0; ?>
            <?php foreach ($themeConfig as $key => $value) { ?>
                <span class="<?= $i == 0 ? 'orange' : '' ?> pointer"
                      data-type="<?= Html::encode($key) ?>"> <?= Html::encode($value) ?></span>
                <?php $i++; ?>
            <?php } ?>
        </div>
        <div style="height: <?= $config['height'] ?>" id="<?= $boxId; ?>-echarts"></div>
        <?= Html::hiddenInput('server', $config['server']) ?>
        <!-- /.row -->
    </div>

<?php Yii::$app->view->registerJs(<<<JS
    var boxId = "$boxId";
    echartsList[boxId] = echarts.init(document.getElementById(boxId + '-echarts'), '$themeJs');

    // 动态加载数据
    $('#'+ boxId +' div span').click(function () {
        $(this).parent().find('span').removeClass('orange');
        $(this).addClass('orange');
        var type = $(this).data('type');
        var boxId = $(this).parent().parent().attr('id');
        var getUrl = $(this).parent().parent().find('input').val();

        $.ajax({
            type:"get",
            url: getUrl,
            dataType: "json",
            data: {type:type, data: 'echarts'},
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
<?php
use common\helpers\Html;

$col =  12 / $level;
?>

<div class="row">
    <?php if ($level >= 1){ ?>
        <div class="col-lg-<?= $col; ?>">
            <?= $form->field($model, $provincesName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid(), [
                    'prompt' => '-- 请选择省 --',
                    'onchange' => 'widget_provinces(this, 1,"' . Html::getInputId($model, $cityName) . '","' . Html::getInputId($model, $areaName) . '")',
                ]); ?>
        </div>
    <?php }?>
    <?php if ($level >= 2){ ?>
        <div class="col-lg-<?= $col; ?>">
            <?= $form->field($model, $cityName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid($model->$provincesName, 2), [
                    'prompt' => '-- 请选择市 --',
                    'onchange' => 'widget_provinces(this,2,"' . Html::getInputId($model, $areaName) . '","'.Html::getInputId($model, $areaName) . '")',
                ]); ?>
        </div>
    <?php }?>
    <?php if ($level >= 3){ ?>
        <div class="col-lg-<?= $col; ?>">
            <?= $form->field($model, $areaName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid($model->$cityName, 3), [
                'prompt' => '-- 请选择区 --',
            ]) ?>
        </div>
    <?php }?>
</div>

<script>
    function widget_provinces(obj, type_id, cityId, areaId) {
        $(".form-group.field-" + areaId).hide();
        var pid = $(obj).val();
        $.ajax({
            type :"get",
            url : "<?= $url; ?>",
            dataType : "json",
            data : {type_id:type_id, pid:pid},
            success: function(data){
                if (type_id == 2) {
                    $(".form-group.field-"+areaId).show();
                }

                $("select#"+cityId+"").html(data);
            }
        });
    }
</script>
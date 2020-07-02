<?php

use common\helpers\Html;

$col = $level <= 3 ? 12 / $level : 4;
$cityId = $areaId = $townshipId = $villageId = -1;
$level >= 2 && $cityId = Html::getInputId($model, $cityName);
$level >= 3 && $areaId = Html::getInputId($model, $areaName);
$level >= 4 &&$townshipId = Html::getInputId($model, $townshipName);
$level >= 5 &&$villageId = Html::getInputId($model, $villageName);
?>

<div class="row">
    <?php if ($level >= 1) { ?>
        <div class="col-lg-<?= $col; ?>">
            <?= $form->field($model, $provincesName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid(), [
                'prompt' => '-- 请选择省 --',
                'onchange' => "widget_provinces(this, 1, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]); ?>
        </div>
    <?php } ?>
    <?php if ($level >= 2) { ?>
        <div class="col-lg-<?= $col; ?>">
            <?= $form->field($model, $cityName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid($model->$provincesName, 2), [
                'prompt' => '-- 请选择市 --',
                'onchange' => "widget_provinces(this, 2, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]); ?>
        </div>
    <?php } ?>
    <?php if ($level >= 3) { ?>
        <div class="col-lg-<?= $col; ?>">
            <?= $form->field($model, $areaName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid($model->$cityName, 3), [
                'prompt' => '-- 请选择区 --',
                'onchange' => "widget_provinces(this, 3, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]) ?>
        </div>
    <?php } ?>
    <?php if ($level >= 4) { ?>
        <div class="col-lg-<?= $col; ?>">
            <?= $form->field($model, $townshipName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid($model->$areaName, 4), [
                'prompt' => '-- 请选择乡/镇 --',
                'onchange' => "widget_provinces(this, 4, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]) ?>
        </div>
    <?php } ?>
    <?php if ($level >= 5) { ?>
        <div class="col-lg-<?= $col; ?>">
            <?= $form->field($model, $villageName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid($model->$townshipName, 5), [
                'prompt' => '-- 请选择村/社区 --',
                'onchange' => "widget_provinces(this, 5, '$cityId', '$areaId', '$townshipId', '$villageId')",
            ]) ?>
        </div>
    <?php } ?>
</div>

<script>
    function widget_provinces(obj, type_id, cityId, areaId, townshipId, villageId) {
        switch (type_id) {
            case 1 :
                $(".form-group.field-" + areaId).hide();
                $(".form-group.field-" + townshipId).hide();
                $(".form-group.field-" + villageId).hide();
                break;
            case 2 :
                $(".form-group.field-" + areaId).hide();
                $(".form-group.field-" + townshipId).hide();
                $(".form-group.field-" + villageId).hide();
                break;
            case 3 :
                $(".form-group.field-" + townshipId).hide();
                $(".form-group.field-" + villageId).hide();
                break;
            case 4 :
                $(".form-group.field-" + villageId).hide();
                break;
        }

        var pid = $(obj).val();
        $.ajax({
            type: "get",
            url: "<?= $url; ?>",
            dataType: "json",
            data: {type_id: type_id, pid: pid},
            success: function (data) {
                switch (type_id) {
                    case 1 :
                        $("select#" + cityId + "").html(data);
                        break;
                    case 2 :
                        $(".form-group.field-" + areaId).show();
                        $("select#" + areaId + "").html(data);
                        break;
                    case 3 :
                        $(".form-group.field-" + townshipId).show();
                        $("select#" + townshipId + "").html(data);
                        break;
                    case 4 :
                        $(".form-group.field-" + villageId).show();
                        $("select#" + villageId + "").html(data);
                        break;
                }
            }
        });
    }
</script>
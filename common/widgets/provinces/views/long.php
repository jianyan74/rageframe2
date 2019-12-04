<?php
use common\helpers\Html;
use yii\helpers\Url;
?>

<?php if ($level >= 1){ ?>
    <?= $form->field($model, $provincesName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid(), [
            'prompt' => '-- 请选择省 --',
            'onchange' => 'widget_provinces(this, 1,"' . Html::getInputId($model, $cityName) . '","' . Html::getInputId($model, $areaName) . '")',
        ]); ?>
<?php }?>
<?php if ($level >= 2){ ?>
    <?= $form->field($model, $cityName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid($model->$provincesName, 2), [
            'prompt' => '-- 请选择市 --',
            'onchange' => 'widget_provinces(this,2,"' . Html::getInputId($model, $areaName) . '","' . Html::getInputId($model, $areaName) . '")',
        ]); ?>
<?php }?>
<?php if ($level >= 3){ ?>
    <?= $form->field($model, $areaName)->dropDownList(Yii::$app->services->provinces->getCityMapByPid($model->$cityName, 3), [
        'prompt' => '-- 请选择区 --',
    ]) ?>
<?php }?>

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

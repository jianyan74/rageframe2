<?php
use yii\helpers\Html;
use common\models\common\Provinces;

$provinces = $form->field($model, $provincesName)->dropDownList(Provinces::getCityList(),
    [
        'prompt' => '-- 请选择省 --',
        'onchange' => 'widget_provinces(this, 1,"'.Html::getInputId($model, $cityName) . '","' . Html::getInputId($model, $areaName).'")',
    ]);

$city = $form->field($model, $cityName)->dropDownList(Provinces::getCityList($model->$provincesName, true),
    [
        'prompt' => '-- 请选择市 --',
        'onchange' => 'widget_provinces(this,2,"'.Html::getInputId($model, $areaName) . '","'.Html::getInputId($model, $areaName).'")',
    ]);

$area = $form->field($model, $areaName)->dropDownList(Provinces::getCityList($model->$cityName, true),[
    'prompt' => '-- 请选择区 --',
])
?>

<?php if($template == 'short'){ ?>
    <div class="row">
        <div class="col-lg-4">
            <?= $provinces ?>
        </div>
        <div class="col-lg-4">
            <?= $city ?>
        </div>
        <div class="col-lg-4">
            <?= $area ?>
        </div>
    </div>
<?php }else{ ?>
    <?= $provinces ?>
    <?= $city ?>
    <?= $area ?>
<?php } ?>

<script>
    function widget_provinces(obj, type_id, cityId, areaId) {
        $(".form-group.field-"+areaId).hide();
        var pid = $(obj).val();
        $.ajax({
            type :"get",
            url : "<?= $url ?>",
            dataType : "json",
            data : {
                type_id:type_id,
                pid:pid
            },
            success: function(data){
                if (type_id == 2) {
                    $(".form-group.field-"+areaId).show();
                }

                $("select#"+cityId+"").html(data);
            }
        });
    }
</script>
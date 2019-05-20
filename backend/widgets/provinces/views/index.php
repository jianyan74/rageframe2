<?php
use common\helpers\Html;
use common\models\common\Provinces;

$col =  12 / $level;

$level >=1 && $provinces = $form->field($model, $provincesName)->dropDownList(Provinces::getCityList(),
    [
        'prompt' => '-- 请选择省 --',
        'onchange' => 'widget_provinces(this, 1,"'.Html::getInputId($model, $cityName) . '","' . Html::getInputId($model, $areaName).'")',
    ]);

$level >=2 && $city = $form->field($model, $cityName)->dropDownList(Provinces::getCityList($model->$provincesName, true),
    [
        'prompt' => '-- 请选择市 --',
        'onchange' => 'widget_provinces(this,2,"'.Html::getInputId($model, $areaName) . '","'.Html::getInputId($model, $areaName).'")',
    ]);

$level >=3 && $area = $form->field($model, $areaName)->dropDownList(Provinces::getCityList($model->$cityName, true),[
    'prompt' => '-- 请选择区 --',
])
?>

<?php if($template == 'short'){ ?>
    <div class="row">
        <?php if ($level >= 1){ ?>
            <div class="col-lg-<?= $col; ?> <?php if ($level < 1){ echo 'hide';};?>">
                <?= $provinces ?>
            </div>
        <?php }?>
        <?php if ($level >= 2){ ?>
            <div class="col-lg-<?= $col; ?> <?php if ($level < 2){ echo 'hide';};?>">
                <?= $city ?>
            </div>
        <?php }?>
        <?php if ($level >= 3){ ?>
            <div class="col-lg-<?= $col; ?> <?php if ($level < 3){ echo 'hide';};?>">
                <?= $area ?>
            </div>
        <?php }?>
    </div>
<?php }else{ ?>
    <?php if ($level >= 1){ ?>
        <?= $provinces ?>
    <?php }?>
    <?php if ($level >= 2){ ?>
        <?= $city ?>
    <?php }?>
    <?php if ($level >= 3){ ?>
        <?= $area ?>
    <?php }?>
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
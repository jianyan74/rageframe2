<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="row" id="<?= $boxId; ?>">
    <div class="col-lg-12">
        <div class="input-group">
            <?= Html::textInput('address-all', $address, [
                'class' => 'form-control',
                'disabled' => true,
                'placeholder' => '请选择经纬度'
            ]); ?>
            <span class="input-group-btn"><a href="javascript:void (0);" class="btn btn-white map-edit">编辑</a></span>
            <span class="input-group-btn"><a href="javascript:void (0);" class="btn btn-white map-select">地图选择</a></span>
        </div>
        <div class="hidden">
            <a href="<?= Url::to(['/select-map/input', 'boxId' => $boxId,]) ?>" data-toggle="modal" data-target="#ajaxModal" class="rfEditMap"></a>
            <a href="<?= Url::to(['/select-map/map',
                'type' => $type,
                'defaultSearchAddress' => $defaultSearchAddress,
                'boxId' => $boxId,
            ])?>" class="rfSelectMap" data-toggle="modal" data-target="#ajaxModalMax"></a>
            <?= Html::hiddenInput($name . '[lng]', $value['lng'], ['class' => 'mapLng']); ?>
            <?= Html::hiddenInput($name . '[lat]', $value['lat'], ['class' => 'mapLat']); ?>
        </div>
    </div>
</div>

<script>
    var boxId = "<?= $boxId; ?>";
    $(document).on('select-map-' + boxId, function(e, boxId, data){
        if (data.lng == 'undefined' || data.lng == undefined) {
            return;
        }

        var str = data.lng + "," + data.lat;
        $('#' + boxId).find('.input-group input').val(str);
        $('#' + boxId).find('.mapLng').val(data.lng);
        $('#' + boxId).find('.mapLat').val(data.lat);
    });
</script>
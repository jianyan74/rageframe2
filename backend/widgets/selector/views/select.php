<?php
use yii\helpers\Url;
use yii\helpers\Html;
?>

<div class="form-group required">
    <div class="rf-select-list" id="<?= $boxId; ?>">
        <div class="img-box" data-toggle="modal" data-target="#ajaxModalMax" href="<?= Url::to(['/selector/list', 'boxId' => $boxId, 'media_type' => $type])?>">
            <?php if ($type == 'news' || $type == 'image'){ ?>
                <?php if (!empty($model->media_url)){ ?>
                    <?= Html::img(Url::to(['analysis/image','attach' => $model->media_url])) ?>
                <?php }else{ ?>
                    <?= Html::img('@web/resources/dist/img/add-img.png', [
                        'style' => 'height:auto;padding-top:40px'
                    ])?>
                <?php } ?>
            <?php }else{ ?>
                <i class="fa fa-file" style="font-size: 35px;margin:0 auto;padding-top: 40px"></i>
            <?php } ?>
            <div class="bottomBar"><?= !empty($model->file_name) ? $model->file_name : '点击选择'?></div>
        </div>
        <div class="hint-block"><?= $block ?></div>
        <?= Html::hiddenInput($name, $value)?>
    </div>
</div>

<script>
    // 选择回调
    var boxId = "<?= $boxId; ?>";
    $(document).on('select-file-' + boxId, function(e, boxId, data){
        if (data.length === 0) {
            return;
        }

        var dataFirst = data[0];
        if (dataFirst.type == 'image') {
            $('#' + boxId).find('img').attr('src', dataFirst.url);
            $('#' + boxId).find('img').attr('style', '');
        }

        $('#' + boxId).find('input').attr('value', dataFirst.key);
        $('#' + boxId).find('.bottomBar').text(dataFirst.title);
    });
</script>
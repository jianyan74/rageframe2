<?php
$jsConfig = json_encode($config);

?>

<div class="multi-container col-sm-12">
    <div class="file-list">
        <ul data-name = "<?= $name?>" data-boxId = "<?= $boxId?>">
            <?php if($config['pick']['multiple'] == true){ ?>
                <?php foreach ($value as $vo){ ?>
                    <li>
                        <input name="<?= $name?>" value="<?= $vo?>" type="hidden">
                        <div class="img-box">
                            <i class="fa fa-file"></i>
                            <i><?= \common\helpers\StringHelper::clipping($vo)?></i>
                            <i class="delimg" data-multiple="<?= $config['pick']['multiple'] ?>"></i>
                        </div>
                    </li>
                <?php } ?>
                <li class="upload-box upload-album-<?= $boxId?>"></li>
            <?php }else{ ?>
                <?php if($value){ ?>
                    <li>
                        <input name="<?= $name?>" value="<?= $value?>" type="hidden">
                        <div class="img-box">
                            <i class="fa fa-file"></i>
                            <i><?= \common\helpers\StringHelper::clipping($value)?></i>
                            <i class="delimg" data-multiple="<?= $config['pick']['multiple'] ?>"></i>
                        </div>
                    </li>
                <?php } ?>
                <li class="upload-box upload-album-<?= $boxId ?>" <?php if(!empty($value)){?> style="display: none"<?php } ?>></li>
            <?php } ?>
        </ul>
    </div>
</div>

<?php $this->registerJs(<<<Js
    $(".upload-album-{$boxId}").InitMultiUploader({$jsConfig});
Js
);
?>

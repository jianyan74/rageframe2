<?php
$jsConfig = json_encode($config);

?>

<div class="multi-container col-sm-12">
    <div class="photo-list">
        <ul data-name = "<?= $name?>" data-boxId = "<?= $boxId?>">
            <?php if($config['pick']['multiple'] == true){ ?>
                <?php foreach ($value as $vo){ ?>
                    <li>
                        <input name="<?= $name?>" value="<?= $vo?>" type="hidden">
                        <div class="img-box">
                            <a href="<?= trim($vo) ?>" data-fancybox="rfUploadImg">
                                <div class="backgroundCover" style="background-image: url(<?= $vo?>);height: 110px"></div>
                            </a>
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
                            <a href="<?= $value ?>" data-fancybox="rfUploadImg">
                                <div class="backgroundCover" style="background-image: url(<?= $value?>);height: 110px"></div>
                            </a>
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

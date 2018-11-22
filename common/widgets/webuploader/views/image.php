<?php

$jsConfig = json_encode($config);
?>

<div class="multi-container col-sm-12">
    <div class="photo-list">
        <ul data-name = "<?= $name?>" data-boxId = "<?= $boxId?>" id="<?= $boxId?>">
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

<script>
    var mergeUrl = "<?= \yii\helpers\Url::to(['/file/merge']) ?>";
</script>
<?php $this->registerJs(<<<Js
    $(".upload-album-{$boxId}").InitMultiUploader({$jsConfig});

    var el = document.getElementById('{$boxId}');
    var sortable = Sortable.create(el,{});
    // 兼容老IE
    document.body.ondrop = function (event) {
        event = event || window.event;
        if (event.preventDefault) {
            event.preventDefault();
            event.stopPropagation();
        } else {
            event.returnValue = false;
            event.cancelBubble = true;
        }
    };
Js
);
?>

<?php
use yii\helpers\Url;
$jsConfig = json_encode($config);
?>

<div class="row">
    <div class="multi-container col-sm-12 rf-m">
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
                    <li class="upload-box upload-album-<?= $boxId?>"  data-select="<?= $config['select']?>"></li>
                    <div class="halfOpacityBlackBG absoluteFullSize" style="display: none;width: 110px;height: 110px;">
                        <a class="fontColorWhite uploadWebuploader" href="javascript:void(0)" style="left:25%;top: 25%;position: absolute;">上传图片</a>
                        <a class="fontColorWhite" href="<?= Url::to(['/file/attachment', 'boxId' => $boxId, 'multiple' => true])?>" style="right:25%;top: 55%;position: absolute;" data-toggle='modal' data-target='#ajaxModalMax'>选择图片</a>
                    </div>
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
                    <li class="upload-box upload-album-<?= $boxId ?>" <?php if(!empty($value)){?> style="display: none"<?php } ?>  data-select="<?= $config['select']?>"></li>
                    <div class="halfOpacityBlackBG absoluteFullSize" style="display: none;width: 110px;height: 110px;">
                        <a class="fontColorWhite uploadWebuploader" href="javascript:void(0)" style="left:25%;top: 25%;position: absolute;">上传图片</a>
                        <a class="fontColorWhite" href="<?= Url::to(['/file/attachment', 'boxId' => $boxId, 'multiple' => false])?>" style="right:25%;top: 55%;position: absolute;" data-toggle='modal' data-target='#ajaxModalMax'>选择图片</a>
                    </div>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
<script>
    var mergeUrl = "<?= \yii\helpers\Url::to(['/file/merge']) ?>";
</script>

<?php $this->registerJs(<<<Js
    $('.upload-album-{$boxId}').mouseenter(function(e){
        var obj = $(e.currentTarget);
        if (obj.attr('data-select') == false || obj.attr('data-select') == ''){
            return;
        }
        
        var boxLeft = obj.position().left;
        var boxTop = obj.position().top + 15;
       obj.parent().find('.halfOpacityBlackBG').css({left:boxLeft+'px',top:boxTop+'px'})
        if (!obj.is(":hidden")) {
            obj.parent().find('.halfOpacityBlackBG').show();
        }
    });
    
    $(".upload-album-{$boxId}").InitMultiUploader({$jsConfig});
    var sortable = Sortable.create(document.getElementById('{$boxId}'),{});
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
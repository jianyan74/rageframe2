<?php
use common\helpers\ImageHelper;
use yii\helpers\Html;
use yii\helpers\Url;
?>

<div class="rf-row">
    <div class="col-sm-12">
        <div class="upload-list">
            <ul id="<?= $boxId; ?>">
                <li class="<?= $config['circle'] == true ? 'img-circle' : ''?>">
                    <div class="img-box headPortrait">
                        <?= Html::hiddenInput($name, $value)?>
                        <a href="<?= trim(ImageHelper::defaultHeaderPortrait($value, '/resources/dist/img/default-cropper.jpg')) ?>" data-fancybox="rfUploadImg">
                            <div class="bg-cover" style="background-image: url(<?= ImageHelper::defaultHeaderPortrait($value, '/resources/dist/img/default-cropper.jpg'); ?>);"></div>
                        </a>
                        <div class="bottom-bar" href="<?= Url::to(['/cropper/crop', 'boxId' => $boxId])?>" data-toggle="modal" data-target="#ajaxModalLg">编辑</div>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<script>
    var boxId = "<?= $boxId ?>";
    // 上传成功
    $(document).on('cropper-upload-' + boxId, function(e, src){
        var cropperConfig = JSON.parse('<?= json_encode($config); ?>');
        var formDataConfig = JSON.parse('<?= json_encode($formData); ?>');
        formDataConfig.image = src;

        $.ajax({
            url : cropperConfig.server,
            type : "post",
            dataType : 'json',
            data : formDataConfig,
            success : function(data) {
                if(data.code == 200) {
                    data = data.data;
                    $('#' + boxId).find('.bg-cover').attr('style', "background-image: url("+data.url+");");
                    $('#' + boxId).find('a').attr('href', data.url);
                    $('#' + boxId).find('input').val(data.url);
                }else{
                    rfError(data.message)
                }
            }
        });
    });
</script>
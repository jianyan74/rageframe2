<?php

use yii\helpers\Html;
use yii\helpers\Url;

?>

<div class="rf-row">
    <div class="col-sm-12">
        <div class="upload-list">
            <ul id="<?= $boxId; ?>" data-name="<?= $name ?>" data-boxId="<?= $boxId ?>"
                data-multiple="<?= $config['multiple'] ?>">
                <?php foreach ($value as $vo) { ?>
                    <li>
                        <?= Html::hiddenInput($name, $vo) ?>
                        <div class="img-box">
                            <a href="<?= trim($vo) ?>" data-fancybox="rfUploadImg">
                                <div class="bg-cover" style="background-image: url(<?= $vo ?>);"></div>
                            </a>
                            <i class="delimg" data-multiple="<?= $config['multiple'] ?>"></i>
                        </div>
                    </li>
                <?php } ?>
                <li class="upload-box crop-upload <?php if (!empty($value) && $config['multiple'] == false) { ?>hide<?php } ?>">
                    <i class="fa fa-crop"></i>
                    <?php if ($themeConfig['select'] === true) { ?>
                        <div class="upload-box-bg hide befor-upload">
                            <a class="first"
                               href="<?= Url::to(['/file/selector', 'boxId' => $boxId, 'upload_type' => $type, 'multiple' => $config['multiple'], 'upload_drive' => $formData['drive']]) ?>"
                               data-toggle='modal' data-target='#ajaxModalMax'>选择图片</a>
                            <a class="second crop-upload" onclick="boxId">裁剪上传</a>
                        </div>
                    <?php } ?>
                </li>
            </ul>
        </div>
    </div>
</div>

<!--隐藏上传组件-->
<div class="hidden" id="crop-upload-<?= $boxId; ?>">
    <a href="<?= Url::to(['/cropper/crop', 'boxId' => $boxId, 'multiple' => $config['multiple'], 'aspectRatio' => $config['aspectRatio']]) ?>" data-toggle="modal" data-target="#ajaxModalLg"></a>
</div>

<!--模板-->
<script type="text/html" id="tpl-<?= $boxId; ?>">
    <li>
        <input type="hidden" name="<?= $name; ?>" value="{{value}}">
        <div class="img-box">
            <a href="{{value}}" data-fancybox="rfUploadImg">
                <div class="bg-cover" style="background-image: url({{value}});"></div>
            </a>
            <i class="delimg" data-multiple="{{multiple}}"></i>
        </div>
    </li>
</script>

<script>
    var boxId = "<?= $boxId ?>";
    // 上传成功
    $(document).on('cropper-upload-' + boxId, function (e, src, width, height, multiple, boxId) {
        var cropperConfig = JSON.parse('<?= json_encode($config); ?>');
        var formDataConfig = JSON.parse('<?= json_encode($formData); ?>');
        formDataConfig.image = src;
        formDataConfig.width = width;
        formDataConfig.height = height;

        console.log(multiple)

        $.ajax({
            url: cropperConfig.server,
            type: "post",
            dataType: 'json',
            data: formDataConfig,
            success: function (data) {
                if (data.code == 200) {
                    data = data.data;

                    // 判断是否是多图上传
                    let obj = $('#' + boxId + ' .upload-box');
                    if (multiple === 'false' || multiple === false || multiple == '0'){
                        $(obj).addClass('hide');
                    }

                    // 增加显示
                    let callData = [];
                    callData["id"] = data.id;
                    callData["value"] = data.url;
                    callData["multiple"] = multiple;
                    let html = template('tpl-' + boxId, callData);

                    // 查找文本框并移除
                    $(obj).parent().find('#hideInput-' + boxId).remove();
                    $(obj).before(html);
                } else {
                    rfError(data.message)
                }
            }
        });
    });

    // 删除图片节点
    $(document).on("click", ".delimg", function () {
        let parentObj = $(this).parent().parent();
        let multiple = $(this).data('multiple');
        let name = parentObj.parent().attr('data-name');
        let boxId = parentObj.parent().attr('data-boxId');

        if (multiple == true) {
            name = name.substring(0, name.length - 2);
        }

        let input = '<input type="hidden" name="' + name + '" value="" id="hideInput-' + boxId + '">';

        // 判断是否是多图上传
        if (multiple === '' || multiple === false || multiple == '0') {
            //增加值为空的隐藏域
            parentObj.parent().append(input);
            //显示上传图片按钮
            parentObj.next("li").removeClass('hide');
        } else {
            // 增加值为空的隐藏域
            let length = parentObj.parent().find('li').length;
            if (length === 2) {
                parentObj.parent().append(input);
            }
        }

        parentObj.remove();
    });

    // 选择回调
    $(document).on('select-file-' + boxId, function (e, boxId, data) {
        if (data.length === 0) {
            return;
        }

        let multiple = $('#' + boxId).data('multiple');
        // 判断是否是多图上传
        let obj = $('#' + boxId + ' .upload-box');
        if (multiple === 'false' || multiple === false || multiple === '' || multiple == '0') {
            $(obj).addClass('hide');
            // 增加显示
            var arr = data[0].url.split('.');
            let callData = [];
            callData["id"] = data[0].id;
            callData["value"] = data[0].url;
            callData["upload_type"] = data[0].upload_type;
            callData["extend"] = '.' + arr[arr.length - 1];
            callData["multiple"] = multiple;
            let html = template('tpl-' + boxId, callData);
            $(obj).before(html);
        } else {
            for (let i = 0; i < data.length; i++) {
                // 增加显示
                var arr = data[i].url.split('.');
                let callData = [];
                callData["id"] = data[i].id;
                callData["value"] = data[i].url;
                callData["upload_type"] = data[i].upload_type;
                callData["extend"] = '.' + arr[arr.length - 1];
                callData["multiple"] = multiple;
                let html = template('tpl-' + boxId, callData);
                $(obj).before(html);
            }
        }

        // 查找文本框并移除
        $(obj).parent().find('#hideInput-' + boxId).remove();
    });
</script>
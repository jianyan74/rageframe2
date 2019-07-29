<?php
use yii\helpers\Html;
use yii\helpers\Url;
use common\helpers\ImageHelper;
use common\helpers\StringHelper;
?>

<div class="rf-row">
    <div class="col-sm-12">
        <div class="upload-list">
            <ul id="<?= $boxId; ?>" data-name="<?= $name?>" data-boxId="<?= $boxId?>" data-multiple="<?= $config['pick']['multiple'] ?>">
                <?php foreach ($value as $vo){ ?>
                    <li>
                        <?= Html::hiddenInput($name, $vo)?>
                        <div class="img-box">
                            <?php if ($type == 'images' || ImageHelper::isImg($vo)) {?>
                                <a href="<?= trim($vo) ?>" data-fancybox="rfUploadImg">
                                    <div class="bg-cover" style="background-image: url(<?= $vo?>);"></div>
                                </a>
                            <?php } else { ?>
                                <i class="fa fa-file-o"></i>
                                <i class="upload-extend"><?= StringHelper::clipping($vo) ?></i>
                                <div class="bottom-bar"><a href="<?= $vo ?>" target="_blank">预览</a></div>
                            <?php } ?>
                            <i class="delimg" data-multiple="<?= $config['pick']['multiple'] ?>"></i>
                        </div>
                    </li>
                <?php } ?>
                <li class="upload-box <?php if(!empty($value) && $config['pick']['multiple'] == false){?>hide<?php } ?>">
                    <i class="fa fa-cloud-upload"></i>
                    <?php if ($themeConfig['select'] === true) {?>
                        <div class="upload-box-bg hide befor-upload">
                            <a class="first" href="<?= Url::to(['/file/selector', 'boxId' => $boxId, 'upload_type' => $type, 'multiple' => $config['pick']['multiple'], 'upload_drive' => $config['formData']['drive']])?>" data-toggle='modal' data-target='#ajaxModalMax'>选择文件</a>
                            <a class="second upload-box-immediately">立即上传</a>
                        </div>
                    <?php } ?>
                    <div class="upload-box-bg hide">
                        <div class="upload-progress first">
                            <span class="badge bg-green">0%</span>
                        </div>
                        <a class="second cancel">取消上传</a>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</div>

<!--模板-->
<script type="text/html" id="tpl-<?= $boxId; ?>">
    <li>
        <input type="hidden" name="<?= $name; ?>" value="{{value}}">
        <div class="img-box">
            {{if upload_type == 'images'}}
                <a href="{{value}}" data-fancybox="rfUploadImg">
                    <div class="bg-cover" style="background-image: url({{value}});"></div>
                </a>
            {{else}}
                <i class="fa fa-file-o"></i>
                <i class="upload-extend">{{extend}}</i>
                <div class="bottom-bar"><a href="{{value}}" target="_blank">预览</a></div>
            {{/if}}
            <i class="delimg" data-multiple="{{multiple}}"></i>
        </div>
    </li>
</script>

<!--隐藏上传组件-->
<div class="hidden" id="upload-<?= $boxId; ?>">
    <div class="upload-album-<?= $boxId; ?>"></div>
</div>

<script>
    var boxId = "<?= $boxId; ?>";
    var closeFiles = {};

    // 删除图片节点
    $(document).on("click", ".delimg", function() {
        let parentObj = $(this).parent().parent();
        let multiple =  $(this).data('multiple');
        let name = parentObj.parent().attr('data-name');
        let boxId = parentObj.parent().attr('data-boxId');

        if (multiple == true) {
            name = name.substring(0, name.length - 2);
        }

        let input = '<input type="hidden" name="' + name + '" value="" id="hideInput-' + boxId + '">';

        // 判断是否是多图上传
        if (multiple === '' || multiple === false) {
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

    // 上传成功
    $(document).on('upload-success-' + boxId, function(e, data, config){
        let boxId = config.boxId;
        let multiple = config.pick.multiple;
        // 判断是否是多图上传
        let obj = $('#' + boxId + ' .upload-box');
        if (multiple === 'false' || multiple === false){
            $(obj).addClass('hide');
        }

        var arr = data.url.split('.');

        // 增加显示
        let callData = [];
        callData["id"] = data.id;
        callData["value"] = data.url;
        callData["extend"] = '.' + arr[arr.length - 1];
        callData["upload_type"] = data.upload_type;
        callData["multiple"] = multiple;
        let html = template('tpl-' + config.boxId, callData);

        // 查找文本框并移除
        $(obj).parent().find('#hideInput-' + boxId).remove();
        $(obj).before(html);
    });

    // 上传失败
    $(document).on('upload-error-' + boxId, function(e, file, reason, uploader, config){
        uploader.removeFile(file); //从队列中移除
        rfError("上传失败，服务器错误");
    });

    // 文件添加进来的时候
    $(document).on('upload-file-queued-' + boxId, function(e, file, uploader, config){
        let parentObj = getParent(config);
    });

    // 一批文件添加进来的时候
    $(document).on('upload-files-queued-' + boxId, function(e, files, uploader, config){
        let parentObj = getParent(config);
    });

    // 上传不管成功还是失败回调
    $(document).on('upload-complete-' + boxId, function(e, file, num, config, uploadProgress){
        let parentObj = getParent(config);
        var remove = true;
        // 如果队列为空，则移除进度条
        jQuery.each(uploadProgress, function(i, val) {
            var tmpVal = parseInt(val);
            if (tmpVal >= -1 && tmpVal < 100 && closeFiles[i] === undefined) {
                remove = false;
            }
        });

        console.log(closeFiles);
        console.log(uploadProgress);

        if (remove === true) {
            parentObj.find(".upload-progress").parent().addClass('hide');
        }
    });

    // 创建进度条
    $(document).on('upload-create-progress-' + boxId, function(e, file, uploader, config){
        let parentObj = getParent(config);
        if (parentObj.children(".upload-progress").hasClass('hide')) {
            parentObj.children(".badge").html("0%");
            let progressCancel = parentObj.find('.cancel');
            //绑定点击事件
            progressCancel.click(function() {
                uploader.cancelFile(file);
                closeFiles[file.id] = true;
                parentObj.find('.upload-progress').parent().addClass('hide');
            });

            parentObj.find('.upload-progress').parent().removeClass('hide');
        }
    });

    // 实时进度条
    $(document).on('upload-progress-' + boxId, function(e, file, percentage, config){
        let parentObj = getParent(config);
        let progressObj = parentObj.find(".upload-progress");
        percentage = Math.floor(percentage * 100);

        if (percentage > 1) {
            percentage -= 1;
        }

        progressObj.find(".badge").attr('percentage', percentage);
        progressObj.find(".badge").html(percentage + "%");
    });

    // md5创建验证中
    $(document).on('md5Verify-create-progress-' + boxId, function(e, file, uploader, config, text = "验证中..."){
        let parentObj = getParent(config);
        if (parentObj.children(".upload-progress").length === 0) {
            parentObj.find(".badge").html(text);
            let progressCancel = parentObj.find('.cancel');
            //绑定点击事件
            progressCancel.click(function() {
                uploader.cancelFile(file);
                parentObj.find('.upload-progress').parent().addClass('hide');
            });

            parentObj.find('.upload-progress').parent().removeClass('hide');
        }
    });

    // 选择回调
    $(document).on('select-file-' + boxId, function(e, boxId, data){
        if (data.length === 0) {
            return;
        }

        let multiple =  $('#' + boxId).data('multiple');
        // 判断是否是多图上传
        let obj = $('#' + boxId + ' .upload-box');
        if (multiple === 'false' || multiple === false || multiple === ''){
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

    // 获取当前的父类
    function getParent(config) {
        let boxId = config.boxId;
        return $('#' + boxId);
    }
</script>
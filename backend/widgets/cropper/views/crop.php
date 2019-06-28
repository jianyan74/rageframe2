<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
    <h4 class="modal-title">图片裁剪</h4>
</div>

<div class="modal-body">
    <div class="avatar-body">
        <div class="row">
            <div class="col-md-3">
                仅支持 jpeg/png 图片类型
            </div>
            <div class="col-md-9">
                <div class="avatar-upload pull-right">
                    <button class="btn btn-primary"  type="button" onClick="$('input[id=avatarInput]').click();">立即选择</button>
                    <input type="file" accept="image/jpeg, image/png" name="file" id="avatarInput" class="hidden" onchange="selectImg(this)">
                </div>
            </div>
            <div class="col-md-9">
                <div class="avatar-wrapper">
                    <img id="tailoringImg">
                </div>
            </div>
            <div class="col-md-3">
                <div class="avatar-preview preview-lg"></div>
                <div class="avatar-preview preview-md"></div>
                <div class="avatar-preview preview-sm"></div>
            </div>
        </div>
        <div class="row avatar-btns">
            <div class="col-md-3">
                <span class="btn btn-white fa fa-undo" data-method="rotate" data-option="-90" title="向左旋转90°"> 左旋转</span>
                <span class="btn btn-white fa fa-repeat" data-method="rotate" data-option="90" title="向右旋转90°"> 右旋转</span>
            </div>
            <div class="col-md-6" style="text-align: right;">
                <span class="btn btn-white fa fa-arrows" data-method="setDragMode" data-option="move" title="移动"> 移动</span>
                <div class="btn btn-white fa fa-exchange cropper-scaleX" title="换向"> 换向</div>
                <span class="btn btn-white fa fa-search-plus" data-method="zoom" data-option="0.1" title="放大图片"> 放大</span>
                <span class="btn btn-white fa fa-search-minus" data-method="zoom" data-option="-0.1" title="缩小图片"> 缩小</span>
                <span type="button" class="btn btn-white fa fa-refresh" data-method="reset" title="重置图片"> 重置</span>
            </div>
        </div>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button type="button" class="btn btn-primary avatar-save" data-dismiss="modal">保存</button>
</div>

<script>
    var boxId = "<?= $boxId;?>";

    //cropper图片裁剪
    $('#tailoringImg').cropper({
        aspectRatio: 1/1,//默认比例
        preview: '.avatar-preview',//预览视图
        guides: false,  //裁剪框的虚线(九宫格)
        autoCropArea: 0.5,  //0-1之间的数值，定义自动剪裁区域的大小，默认0.8
        dragCrop: true,  //是否允许移除当前的剪裁框，并通过拖动来新建一个剪裁框区域
        movable: true,  //是否允许移动剪裁框
        resizable: true,  //是否允许改变裁剪框的大小
        zoomable: true,  //是否允许缩放图片大小
        mouseWheelZoom: false,  //是否允许通过鼠标滚轮来缩放图片
        touchDragZoom: true,  //是否允许通过触摸移动来缩放图片
        rotatable: true,  //是否允许旋转图片
        minContainerWidth : 600, // 容器的最小宽度
        minContainerHeight : 364, // 容器的最小高度
        crop: function(e) {
            // 输出结果数据裁剪图像。
        }
    });

    $(".avatar-save").on("click", function() {
        if ($("#tailoringImg").attr("src") == null ){
            return false;
        }else{
            var cas = $('#tailoringImg').cropper('getCroppedCanvas');//获取被裁剪后的canvas
            var base64url = cas.toDataURL('image/png'); //转换为base64地址形式
            var base64 = base64url.split(',');

            $(document).trigger('cropper-upload-' + boxId, [base64[1]]);
        }
    });
</script>
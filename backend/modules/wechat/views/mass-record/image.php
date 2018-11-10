<?php
use yii\helpers\Url;

?>

<div class="form-group required">
    <label class="control-label">图片</label>
    <div class="rule-photo-list">
        <div class="img-box" data-toggle="modal" data-target="#baseModel" onclick="indexAttachment()">
            <img src="<?= isset($model->attachment->media_url) ? Url::to(['analysis/image','attach' => $model->attachment->media_url]) : '/backend/resources/img/add-img.png'?>" id="image_url">
            <div class="bottomBar"><?= isset($model->attachment->file_name) ? $model->attachment->file_name : '点击选择照片'?></div>
        </div>
        <input name="SendForm[media_id]" value="<?= $model->media_id ?>" id="media_id" type="hidden">
    </div>
</div>

<!--模板列表-->
<script type="text/html" id="listModelScript">
    {{each data as value i}}
    <div class="normalPaddingRight" style="width:20%;margin-top: 10px;" data-image_url="{{value.image_url}}" data-file_name="{{value.file_name}}" data-media_id="{{value.media_id}}" onclick="selectAttachment($(this))">
        <div class="borderColorGray separateChildrenWithLine whiteBG" style="margin-bottom: 20px;">
            <div class="normalPadding rule-ajax-img">
                <div style="background-image: url({{value.image_url}}); height: 160px;" class="backgroundCover relativePosition mainPostCover">
                    <div class="bottomBar">{{value.file_name}}</div>
                </div>
            </div>
        </div>
    </div>
    {{/each}}
</script>

<script>
    var page = 1;

    function beforSubmit() {
        var media_id =  $('#media_id').val();
        if (!media_id){
            rfAffirm('请选择照片');
            return false;
        }

        $('#w0').submit();
    }

    // 首次打开加载资源
    function indexAttachment() {
        if (page == 1){
            getAttachment()
        }
    }

    // 加载资源
    function getAttachment() {
        $.ajax({
            type:"get",
            url:"<?= \yii\helpers\Url::to(['common/select-attachment'])?>",
            dataType: "json",
            data: {
                page:page,
                media_type: 'image'
            },
            success: function(data){
                if (data.code == 200) {
                    if (data.data.length > 0){
                        page++;
                        var html = template('listModelScript', data);
                        // 渲染添加数据
                        $('#attachmentList').append(html);
                        $('#loadingAttachment').html('<span onclick="getAttachment()" class="btn btn-white">点击加载更多</span>');
                    } else {
                        $('#loadingAttachment').text('没有更多数据了');
                    }
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }

    // 获取选中的照片
    function selectAttachment(that) {
        $('#image_url').attr('src', that.attr('data-image_url'));
        $('#media_id').val(that.attr('data-media_id'));
        $('.rule-photo-list .bottomBar').text(that.attr('data-file_name'));

        $('#baseModel').modal('hide');
    }
</script>
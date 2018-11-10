<?php
use yii\helpers\Url;

?>

<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>回复内容</h5>
    </div>
    <div class="ibox-content">
        <div class="form-group required">
            <label class="control-label">图文</label>
            <div class="rule-photo-list">
                <div class="img-box" data-toggle="modal" data-target="#baseModel" onclick="indexAttachment()">
                    <img src="<?= isset($moduleModel->newsTop->thumb_url) ? Url::to(['analysis/image','attach' => $moduleModel->newsTop->thumb_url]) : '/backend/resources/img/add-img.png'?>" id="image_url">
                    <div class="bottomBar"><?= isset($moduleModel->newsTop->title) ? $moduleModel->newsTop->title : '点击选择图文'?></div>
                </div>
                <div class="hint-block">由于微信限制，自动回复只能回复一条图文信息，如果有多条图文，默认选择第一条图文</div>
                <input name="ReplyNews[attachment_id]" value="<?= !empty($moduleModel->attachment_id) ? $moduleModel->attachment_id : '' ?>" id="attachment_id" type="hidden">
            </div>
        </div>
        <div class="form-group">　
            <div class="col-sm-12 text-center">
                <div class="hr-line-dashed"></div>
                <span class="btn btn-primary" onclick="beforSubmit()">保存</span>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>

<!--模板列表-->
<script type="text/html" id="listModelScript">
    {{each data as value i}}
    <div class="normalPaddingRight" style="width:20%;margin-top: 10px;" data-image_url="{{value.image_url}}" data-title="{{value.title}}" data-attachment_id="{{value.attachment_id}}" onclick="selectAttachment($(this))">
        <div class="borderColorGray separateChildrenWithLine whiteBG" style="margin-bottom: 20px;">
            <div class="normalPadding rule-ajax-img">
                <div style="background-image: url({{value.image_url}}); height: 160px;" class="backgroundCover relativePosition mainPostCover">
                    <div class="bottomBar">{{value.title}}</div>
                </div>
            </div>
        </div>
    </div>
    {{/each}}
</script>

<script>
    var page = 1;

    function beforSubmit() {
        var attachment_id =  $('#attachment_id').val();
        if (!attachment_id){
            rfAffirm('请选择图文');
            return false;
        }

        $('#w0').submit();
    }

    function indexAttachment() {
        if (page == 1){
            getAttachment()
        }
    }

    function getAttachment() {
        $.ajax({
            type:"get",
            url:"<?= \yii\helpers\Url::to(['common/select-news'])?>",
            dataType: "json",
            data: {
                page:page
            },
            success: function(data){
                if (data.code == 200) {
                    if(data.data.length > 0){
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
        $('#attachment_id').val(that.attr('data-attachment_id'));
        $('.rule-photo-list .bottomBar').text(that.attr('data-title'));

        $('#baseModel').modal('hide');
    }
</script>
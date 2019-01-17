<?php
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>

<style>
    .normalPaddingRight .active {
        border: 2px solid #1ab394 !important;
    }
</style>

<!--模板列表-->
<script type="text/html" id="rfAttachmentlistModel">
    {{each data as value i}}
    <div class="normalPaddingRight" style="width:20%;margin-top: 10px;">
        <div class="borderColorGray separateChildrenWithLine whiteBG rfAttachmentActive" style="margin-bottom: 20px;" data-url="{{value.base_url}}">
            <div class="normalPadding rule-ajax-img">
                {{if value.upload_type == "images"}}
                <div style="background-image: url({{value.base_url}}); height: 160px;" class="backgroundCover relativePosition mainPostCover">
                    <div class="bottomBar">{{value.name}}</div>
                </div>
                {{else}}
                <div style="height: 160px;text-align:center;" class="backgroundCover relativePosition mainPostCover">
                    <i class="fa fa-file" style="font-size: 35px;margin:0 auto;padding-top: 40px"></i>
                    <i style="font-size: 20px;margin:0 auto;padding-top: 30px">.{{value.extension}}</i>
                    <div class="bottomBar">{{value.name}}11</div>
                </div>
                {{/if}}
            </div>
        </div>
    </div>
    {{/each}}
</script>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
    <h4 class="modal-title">文件列表</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-3">
            <?= Html::dropDownList('year', '', ArrayHelper::merge(['' => '不限年份'] , $year), [
                'class' => 'form-control',
                'id' => 'rfYear',
            ])?>
        </div>
        <div class="col-lg-3">
            <?= Html::dropDownList('month', '', ArrayHelper::merge(['' => '不限月份'] , $month), [
                'class' => 'form-control',
                'id' => 'rfMonth',
            ])?>
        </div>
        <div class="input-group m-b">
            <?= Html::tag('span', '<button class="btn btn-white" onclick="rfAttachmentSearch()"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
        </div>
    </div>
    <div class="inlineBlockContainer col3 vAlignTop" id="rfAttachmentList">
        <?php foreach ($models as $model){ ?>
            <div class="normalPaddingRight" style="width:20%;margin-top: 10px;">
                <div class="borderColorGray separateChildrenWithLine whiteBG rfAttachmentActive" style="margin-bottom: 20px;" data-url="<?= $model['base_url']?>">
                    <div class="normalPadding">
                        <?php if ($model['upload_type'] == 'images'){?>
                            <div style="background-image: url(<?= $model['base_url']?>); height: 160px;" class="backgroundCover relativePosition mainPostCover">
                                <div class="bottomBar"><?= $model['name']?></div>
                            </div>
                        <?php }else{ ?>
                            <div style="height: 160px;text-align:center;" class="backgroundCover relativePosition mainPostCover">
                                <i class="fa fa-file" style="font-size: 35px;margin:0 auto;padding-top: 40px"></i>
                                <i style="font-size: 20px;margin:0 auto;padding-top: 30px">.<?= $model['extension']?></i>
                                <div class="bottomBar"><?= $model['name'] ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="row text-center" id="loadingAttachment">
        <span onclick="rfGetAttachment()" class="btn btn-white">加载更多</span>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" data-dismiss="modal" id="rfAttachmentDetermine">确定</button>
</div>

<script>
    var page = 2;
    var addImageStatu = true;
    var upload_type = "<?= $upload_type;?>";
    var year = month = '';

    $('#rfAttachmentDetermine').click(function () {
        if (upload_type == 'images') {
            rfImageAdd();
        } else {
            rfFileAdd();
        }
    });

    function rfFileAdd() {
        $('.normalPaddingRight .active').each(function(i, data){
            if (addImageStatu == false){
                return;
            }

            var url = $(data).attr('data-url');
            var multiple = "<?= $multiple?>";
            var boxId = "<?= $boxId?>";
            var parentObj = $('#' + boxId).find('.upload-box');
            var name = parentObj.parent().attr('data-name');

            var arr = url.split('.');
            var newLi = $('<li>'
                + '<input type="hidden" name="'+ name +'" value="'+ url +'" />'
                + '<div class="img-box">'
                + '<i class="fa fa-file"></i>'
                + '<i> .'+ arr[arr.length - 1] +'</i>'
                + '<i class="delimg" data-multiple="'+ multiple +'"></i>'
                + '</div>'
                + '</li>');

            // 判断是否是多文件上传
            if (multiple == 'false' || multiple == false){
                parentObj.hide();
                addImageStatu = false;
            }

            // 查找文本框并移除
            parentObj.parent().find('#'+boxId).remove();
            parentObj.before(newLi);
        });
    }

    function rfImageAdd(){
        $('.normalPaddingRight .active').each(function(i, data){
            if (addImageStatu == false){
                return;
            }

            var url = $(data).attr('data-url');
            var multiple = "<?= $multiple?>";
            var boxId = "<?= $boxId?>";
            var parentObj = $('#' + boxId).find('.upload-box');
            var name = parentObj.parent().attr('data-name');
            var newLi = $('<li>'
                + '<input type="hidden" name="'+ name +'" value="'+ url +'" />'
                + '<div class="img-box">'
                + '<a data-fancybox="rfUploadImg" href="'+ url +'">'
                + '<div class="backgroundCover" style="background-image: url(' + url + ');height: 110px"/>'
                + '</a>'
                + '<i class="delimg" data-multiple="'+ multiple +'"></i>'
                + '</div>'
                + '</li>');

            // 判断是否是多图上传
            if (multiple == 'false' || multiple == false){
                parentObj.hide();
                addImageStatu = false;
            }

            // 查找文本框并移除
            parentObj.parent().find('#'+boxId).remove();
            parentObj.before(newLi);
        });
    }

    /**
     * 搜索
     */
    function rfAttachmentSearch() {
        year = $('#rfYear').val();
        month = $('#rfMonth').val();
        page = 1;

        $('#rfAttachmentList').html('');
        rfGetAttachment();
    }

    function rfGetAttachment() {
        $.ajax({
            type:"get",
            url:"<?= Url::to(['/file/ajax-attachment'])?>",
            dataType: "json",
            data: {
                page:page,
                upload_type: upload_type,
                year: year,
                month: month,
            },
            success: function(data){
                if (data.code == 200) {
                    if (data.data.length > 0){
                        page++;
                        var html = template('rfAttachmentlistModel', data);
                        // 渲染添加数据
                        $('#rfAttachmentList').append(html);
                        $('#loadingAttachment').html('<span onclick="rfGetAttachment()" class="btn btn-white">加载更多</span>');
                    } else {
                        $('#loadingAttachment').text('没有更多数据了');
                    }
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }
</script>
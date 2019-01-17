<?php
use yii\helpers\Html;
use yii\helpers\Url;
?>

<style>
    .normalPaddingRight .active {
        border: 2px solid #1ab394 !important;
    }
</style>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
    <h4 class="modal-title">文件列表</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-3">
            <?= Html::input('text', 'keyword', '', [
                'class' => 'form-control',
                'id' => 'rfKeyword'
            ]); ?>
        </div>
        <div class="input-group m-b">
            <?= Html::tag('span', '<button class="btn btn-white" onclick="rfAttachmentSearch()"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
        </div>
    </div>
    <div class="inlineBlockContainer col3 vAlignTop" id="rfAttachmentList">
        <?php foreach ($models as $model){ ?>
            <div class="normalPaddingRight" style="width:20%;margin-top: 10px;">
                <div class="borderColorGray separateChildrenWithLine whiteBG rfAttachmentActive" style="margin-bottom: 20px;" data-url="<?= $model['imgUrl']?>" data-key="<?= $model['key']?>" data-title="<?= $model['title']?>">
                    <div class="normalPadding">
                        <?php if ($model['type'] == 'image'){?>
                            <div style="background-image: url(<?= $model['imgUrl']?>); height: 160px;" class="backgroundCover relativePosition mainPostCover">
                                <div class="bottomBar"><?= $model['title']?></div>
                            </div>
                        <?php }else{ ?>
                            <div style="height: 160px;text-align:center;" class="backgroundCover relativePosition mainPostCover">
                                <i class="fa fa-file" style="font-size: 35px;margin:0 auto;padding-top: 40px"></i>
                                <div class="bottomBar"><?= $model['title'] ?></div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="row text-center" id="loadingAttachment">
        <span onclick="rfGetWechatAttachment()" class="btn btn-white">加载更多</span>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" data-dismiss="modal" id="rfAttachmentDetermine">确定</button>
</div>

<!--模板列表-->
<script type="text/html" id="rfAttachmentlistModel">
    {{each data as value i}}
    <div class="normalPaddingRight" style="width:20%;margin-top: 10px;">
        <div class="borderColorGray separateChildrenWithLine whiteBG rfAttachmentActive" style="margin-bottom: 20px;" data-title="{{value.title}}" data-key="{{value.key}}" data-url="{{value.imgUrl}}">
            <div class="normalPadding rule-ajax-img">
                {{if value.type == "image"}}
                <div style="background-image: url({{value.imgUrl}}); height: 160px;" class="backgroundCover relativePosition mainPostCover">
                    <div class="bottomBar">{{value.title}}</div>
                </div>
                {{else}}
                <div style="height: 160px;text-align:center;" class="backgroundCover relativePosition mainPostCover">
                    <i class="fa fa-file" style="font-size: 35px;margin:0 auto;padding-top: 40px"></i>
                    <div class="bottomBar">{{value.title}}</div>
                </div>
                {{/if}}
            </div>
        </div>
    </div>
    {{/each}}
</script>

<script>
    var page = 2;
    var addImageStatu = true;
    var type = "<?= $media_type;?>";
    var boxId = "<?= $boxId?>";
    var keyword = '';

    $('#rfAttachmentDetermine').click(function () {
        if (type == 'image' || type == 'news') {
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

            var key = $(data).attr('data-key');
            var title = $(data).attr('data-title');

            $('#' + boxId).find('input').attr('value', key);
            $('#' + boxId).find('.bottomBar').text(title);
            addImageStatu = false;
        });
    }

    function rfImageAdd(){
        $('.normalPaddingRight .active').each(function(i, data){
            if (addImageStatu == false){
                return;
            }

            var url = $(data).attr('data-url');
            var key = $(data).attr('data-key');
            var title = $(data).attr('data-title');

            $('#' + boxId).find('img').attr('src', url);
            $('#' + boxId).find('input').attr('value', key);
            $('#' + boxId).find('.bottomBar').text(title);
            addImageStatu = false;
        });
    }

    /**
     * 搜索
     */
    function rfAttachmentSearch() {
        keyword = $('#rfKeyword').val();
        page = 1;

        $('#rfAttachmentList').html('');
        rfGetWechatAttachment();
    }

    function rfGetWechatAttachment() {
        $.ajax({
            type:"get",
            url:"<?= Url::to(['/wechat-select-attachment/ajax-list'])?>",
            dataType: "json",
            data: {
                page:page,
                media_type: type,
                keyword: keyword,
            },
            success: function(data){
                if (data.code == 200) {
                    if (data.data.length > 0){
                        page++;
                        var html = template('rfAttachmentlistModel', data);
                        // 渲染添加数据
                        $('#rfAttachmentList').append(html);
                        $('#loadingAttachment').html('<span onclick="rfGetWechatAttachment()" class="btn btn-white">加载更多</span>');
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
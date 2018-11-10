<div class="form-group required">
    <label class="control-label">视频</label>
    <div class="rule-photo-list">
        <div class="img-box" data-toggle="modal" data-target="#baseModel" onclick="indexAttachment()">
            <i class="fa fa-play-circle-o" style="font-size: 50px;margin:0 auto;padding-top: 30px"></i>
            <div class="bottomBar"><?= isset($model->attachment->file_name) ? $model->attachment->file_name : '点击选择视频'?></div>
        </div>
        <input name="SendForm[media_id]" value="<?= $model->media_id ?>" id="media_id" type="hidden">
    </div>
</div>

<!--模板列表-->
<script type="text/html" id="listModelScript">
    {{each data as value i}}
    <div class="normalPaddingRight" style="width:20%;margin-top: 10px;" data-file_name="{{value.file_name}}" data-media_id="{{value.media_id}}" onclick="selectAttachment($(this))">
        <div class="borderColorGray separateChildrenWithLine whiteBG" style="margin-bottom: 20px;">
            <div class="normalPadding rule-ajax-img">
                <div style="height: 160px;text-align:center" class="backgroundCover relativePosition mainPostCover">
                    <i class="fa fa-play-circle-o" style="font-size: 50px;margin:0 auto;padding-top: 30px"></i>
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
            rfAffirm('请选择视频');
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
                media_type: 'video'
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

    // 获取选中的数据
    function selectAttachment(that) {
        $('#media_id').val(that.attr('data-media_id'));
        $('.rule-photo-list .bottomBar').text(that.attr('data-file_name'));

        $('#baseModel').modal('hide');
    }
</script>
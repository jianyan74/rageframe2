<div class="ibox float-e-margins">
    <div class="ibox-title">
        <h5>回复内容</h5>
    </div>
    <div class="ibox-content">
        <div class="form-group required">
            <label class="control-label">语音</label>
            <div class="rule-photo-list">
                <div class="img-box" data-toggle="modal" data-target="#baseModel" onclick="indexAttachment()">
                    <i class="fa fa-music" style="font-size: 40px;margin:0 auto;padding-top: 40px"></i>
                    <div class="bottomBar"><?= isset($moduleModel->attachment->file_name) ? $moduleModel->attachment->file_name : '点击选择语音'?></div>
                </div>
                <input name="ReplyVoice[media_id]" value="<?= $moduleModel->media_id ?>" id="media_id" type="hidden">
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
    <div class="normalPaddingRight" style="width:20%;margin-top: 10px;" data-file_name="{{value.file_name}}" data-media_id="{{value.media_id}}" onclick="selectAttachment($(this))">
        <div class="borderColorGray separateChildrenWithLine whiteBG" style="margin-bottom: 20px;">
            <div class="normalPadding rule-ajax-img">
                <div style="height: 160px;text-align:center" class="backgroundCover relativePosition mainPostCover">
                    <i class="fa fa-music" style="font-size: 40px;margin:0 auto;padding-top: 40px"></i>
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
            rfAffirm('请选择语音');
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
                media_type: 'voice'
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
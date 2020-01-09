<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
    <h4 class="modal-title">文件列表</h4>
</div>

<div class="modal-body">
    <div class="row">
        <div class="col-lg-2">
            <?= Html::dropDownList('year', '', ArrayHelper::merge(['' => '不限年份'] , $year), [
                'class' => 'form-control',
                'id' => 'rfYear',
            ])?>
        </div>
        <div class="col-lg-2">
            <?= Html::dropDownList('month', '', ArrayHelper::merge(['' => '不限月份'] , $month), [
                'class' => 'form-control',
                'id' => 'rfMonth',
            ])?>
        </div>
        <div class="col-lg-3">
            <div class="input-group m-b">
                <?= Html::input('text', 'keyword', '', [
                    'class' => 'form-control',
                    'placeholder' => '关键字查询',
                    'id' => 'rfKeyword'
                ]); ?>
                <?= Html::tag('span', '<button class="btn btn-white" onclick="rfAttachmentSearch()"><i class="fa fa-search"></i> 搜索</button>', ['class' => 'input-group-btn'])?>
            </div>
        </div>
        <div class="col-lg-5 text-right">
            <a href="<?= Url::to(['/wechat/attachment/index'])?>" class="openContab btn btn-primary">素材库</a>
        </div>
    </div>
    <ul class="mailbox-attachments clearfix" id="rfAttachmentList"></ul>
    <div class="row text-center m-t" id="loadingAttachment">
        <span onclick="rfGetAttachment()" class="btn btn-white">加载更多</span>
    </div>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" data-dismiss="modal" id="rfAttachmentDetermine">确定</button>
</div>

<!--模板列表-->
<script type="text/html" id="rfAttachmentlistModel">
    {{each data as value i}}
    <li>
        <div class="border-color-gray" data-key="{{value.key}}" data-title="{{value.title}}" data-url="{{value.imgUrl}}" data-type="{{value.type}}">
            {{if value.type == "image"}}
            <span class="mailbox-attachment-icon has-img">
                <img src="{{value.imgUrl}}" style="height: 130px">
            </span>
            {{else}}
            <span class="mailbox-attachment-icon">
                <i class="fa fa-file-o"></i>
            </span>
            {{/if}}
            <div class="mailbox-attachment-info">
                <div class="mailbox-attachment-name">
                    <span><i class="fa fa-paperclip"></i> {{value.title}}</span>
                </div>
            </div>
        </div>
    </li>
    {{/each}}
</script>

<script>
    var page = 2;
    var year = month = keyword = '';
    var boxId = "<?= $boxId;?>";

    // 默认数据
    var defaultPageData = [];
    defaultPageData['data'] = <?= $models; ?>;
    var html = template('rfAttachmentlistModel', defaultPageData);
    // 渲染添加数据
    $('#rfAttachmentList').append(html);

    // 选择
    $('#rfAttachmentDetermine').click(function () {
        let allData = [];
        $('#rfAttachmentList .active').each(function(i, data){
            var tmpData = [];
            tmpData['key'] = $(data).data('key');
            tmpData['url'] = $(data).data('url');
            tmpData['title'] = $(data).data('title');
            tmpData['type'] = $(data).data('type');
            allData.push(tmpData);

            console.log(allData);
        });

        $(document).trigger('select-file-' + boxId, [boxId, allData]);
    });

    /**
     * 搜索
     */
    function rfAttachmentSearch() {
        year = $('#rfYear').val();
        month = $('#rfMonth').val();
        keyword = $('#rfKeyword').val();
        page = 1;

        $('#rfAttachmentList').html('');
        rfGetAttachment();
    }

    function rfGetAttachment() {
        $.ajax({
            type:"get",
            url:"<?= Url::to(['/selector/list', 'media_type' => $media_type, 'json' => true])?>",
            dataType: "json",
            data: {
                page:page,
                year: year,
                month: month,
                keyword: keyword,
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
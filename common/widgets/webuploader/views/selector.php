<?php
use yii\helpers\Url;
use yii\helpers\Html;
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
        <div class="col-lg-2">
            <?= Html::dropDownList('drive', '', ArrayHelper::merge(['' => '不限存储类型'] , $drives), [
                'class' => 'form-control',
                'id' => 'rfDrive',
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
        <div class="col-lg-3 text-right">
            <?= \common\widgets\webuploader\Files::widget([
                'name' => 'upload',
                'value' => '',
                'type' => $upload_type,
                'theme' => 'selector-upload',
                'config' => [
                    'pick' => [
                        'multiple' => true,
                    ],
                ]
            ])?>
        </div>
    </div>
    <ul class="mailbox-attachments clearfix" id="rfAttachmentList" data-multiple="<?= $multiple?>"></ul>
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
        <div class="border-color-gray" data-id="{{value.id}}" data-name="{{value.name}}" data-url="{{value.base_url}}" data-upload_type="{{value.upload_type}}">
            {{if value.upload_type == "images"}}
            <span class="mailbox-attachment-icon has-img">
                <img src="{{value.base_url}}" style="height: 130px">
            </span>
            {{else}}
            <span class="mailbox-attachment-icon">
                <i class="fa fa-file-o"></i>
            </span>
            {{/if}}
            <div class="mailbox-attachment-info">
                <a href="{{value.base_url}}" target="_blank" class="mailbox-attachment-name">
                    <span><i class="fa fa-paperclip"></i> {{value.name}}</span>
                </a>
                <span class="mailbox-attachment-size">
                {{value.size}}
                <a href="{{value.base_url}}" target="_blank" class="btn btn-white btn-xs pull-right"><i class="fa fa-cloud-download"></i></a>
            </span>
            </div>
        </div>
    </li>
    {{/each}}
</script>

<script>
    var page = 2;
    var year = month = keyword = drive = '';
    var boxId = "<?= $boxId;?>";

    // 默认数据
    var defaultPageData = [];
    defaultPageData['data'] = <?= $models; ?>;
    var html = template('rfAttachmentlistModel', defaultPageData);
    // 渲染添加数据
    $('#rfAttachmentList').append(html);

    // 选择图片
    $('#rfAttachmentDetermine').click(function () {
        let allData = [];
        $('#rfAttachmentList .active').each(function(i, data){
            var tmpData = [];
            tmpData['id'] = $(data).data('id');
            tmpData['url'] = $(data).data('url');
            tmpData['name'] = $(data).data('name');
            tmpData['upload_type'] = $(data).data('upload_type');
            allData.push(tmpData)

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
        drive = $('#rfDrive').val();
        page = 1;

        $('#rfAttachmentList').html('');
        rfGetAttachment();
    }

    function rfGetAttachment() {
        $.ajax({
            type:"get",
            url:"<?= Url::to(['/file/selector', 'upload_type' => $upload_type, 'json' => true])?>",
            dataType: "json",
            data: {
                page:page,
                year: year,
                month: month,
                keyword: keyword,
                drive: drive,
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
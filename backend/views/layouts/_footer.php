<?php
use common\helpers\Html;
use common\helpers\Url;
use common\helpers\DebrisHelper;
?>

<!--ajax模拟框加载-->
<div class="modal fade" id="ajaxModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <?= Html::img('@web/resources/dist/img/loading.gif', ['class' => 'loading'])?>
                <span>加载中... </span>
            </div>
        </div>
    </div>
</div>
<!--ajax大模拟框加载-->
<div class="modal fade" id="ajaxModalLg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <?= Html::img('@web/resources/dist/img/loading.gif', ['class' => 'loading'])?>
                <span>加载中... </span>
            </div>
        </div>
    </div>
</div>
<!--ajax最大模拟框加载-->
<div class="modal fade" id="ajaxModalMax" aria-hidden="true">
    <div class="modal-dialog modal-lg" style="width: 80%">
        <div class="modal-content">
            <div class="modal-body">
                <?= Html::img('@web/resources/dist/img/loading.gif', ['class' => 'loading'])?>
                <span>加载中... </span>
            </div>
        </div>
    </div>
</div>
<!--初始化模拟框-->
<div id="rfModalBody" class="hide">
    <div class="modal-body">
        <?= Html::img('@web/resources/dist/img/loading.gif', ['class' => 'loading'])?>
        <span>加载中... </span>
    </div>
</div>

<?php

list($fullUrl, $pageConnector) = DebrisHelper::getPageSkipUrl();
$script = <<<JS
    $(".pagination").append('&nbsp;&nbsp;前往&nbsp;<input id="invalue" type="text" class="pane rf-page-skip-input"/>&nbsp;页');
    $('.rf-page-skip-input').blur(function() {
        var page = $('#invalue').val();
        if (!page) {
            return;
        }
        
        if (parseInt(page) > 0) {
              location.href = "{$fullUrl}" + "{$pageConnector}page="+ parseInt(page);
        } else {
            $('#invalue').val('');
            rfAffirm('请输入正确的页码');
        }
    });
JS;
$this->registerJs($script);
?>

<script>
    // 小模拟框清除
    $('#ajaxModal').on('hide.bs.modal', function (e) {
        if (e.target == this) {
            $(this).removeData("bs.modal");
            $('#ajaxModal').find('.modal-content').html($('#rfModalBody').html());
        }
    });
    // 大模拟框清除
    $('#ajaxModalLg').on('hide.bs.modal', function (e) {
        if (e.target == this) {
            $(this).removeData("bs.modal");
            $('#ajaxModalLg').find('.modal-content').html($('#rfModalBody').html());
        }
    });
    // 最大模拟框清除
    $('#ajaxModalMax').on('hide.bs.modal', function (e) {
        if (e.target == this) {
            $(this).removeData("bs.modal");
            $('#ajaxModalMax').find('.modal-content').html($('#rfModalBody').html());
        }
    });

    // 小模拟框加载完成
    $('#ajaxModal').on('shown.bs.modal', function (e) {
        autoFontColor()
    });
    // 大模拟框加载完成
    $('#ajaxModalLg').on('shown.bs.modal', function (e) {
        autoFontColor()
    });
    // 最模拟框加载完成
    $('#ajaxModalMax').on('shown.bs.modal', function (e) {
        autoFontColor()
    });

    // 启用状态 status 1:启用;0禁用;
    function rfStatus(obj){
        let id = $(obj).parent().parent().attr('id');
        let status = 0; self = $(obj);
        if (self.hasClass("btn-success")){
            status = 1;
        }

        if (!id) {
            id = $(obj).parent().parent().attr('data-key');
        }

        $.ajax({
            type : "get",
            url : "<?= Url::to(['ajax-update'])?>",
            dataType :  "json",
            data : {
                id : id,
                status : status
            },
            success : function(data){
                if (parseInt(data.code) === 200) {
                    if(self.hasClass("btn-success")){
                        self.removeClass("btn-success").addClass("btn-default");
                        self.text('禁用');
                    } else {
                        self.removeClass("btn-default").addClass("btn-success");
                        self.text('启用');
                    }
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }

    // 排序
    function rfSort(obj){
        let id = $(obj).parent().parent().attr('id');
        if (!id) {
            id = $(obj).parent().parent().attr('data-key');
        }

        var sort = $(obj).val();
        if (isNaN(sort)) {
            rfAffirm('排序只能为数字');
            return false;
        } else {
            $.ajax({
                type : "get",
                url : "<?= Url::to(['ajax-update'])?>",
                dataType : "json",
                data : {
                    id : id,
                    sort : sort
                },
                success : function(data) {
                    if (parseInt(data.code) !== 200) {
                        rfAffirm(data.message);
                    }
                }
            });
        }
    }
</script>
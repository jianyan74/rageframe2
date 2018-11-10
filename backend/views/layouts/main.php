<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;
use backend\assets\AppAsset;
use common\widgets\Alert;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!--[if lt IE 8]>
    <meta http-equiv="refresh" content="0;ie.html" />
    <![endif]-->
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="fixed-sidebar full-height-layout gray-bg">
<?php $this->beginBody() ?>
<div class="row wrapper gray-bg page-heading">
    <div class="col-sm-12 m-t-md">
        <div class="pull-left">
            <ol class="breadcrumb gray-bg">
                <?= Breadcrumbs::widget([
                    'homeLink'=>[
                        'label' => Yii::$app->params['adminAcronym'],
                        'url' => "",
                    ],
                    'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                ]) ?>
            </ol>
        </div>
        <div class="pull-right">
            <?php if (Yii::$app->controller->id  != 'main'){ ?>
                <a href="javascript:history.go(-1)" style="color: #999;padding-right: 5px">
                    <i class="fa fa-mail-reply"></i> <font color="#999">返回</font>
                </a>
            <?php } ?>
            <a href="" style="color: #999">
                <i class="glyphicon glyphicon-refresh"></i> 刷新
            </a>
        </div>
    </div>
</div>
<!--massage提示-->
<div style="margin:15px 15px -15px 15px">
    <?= Alert::widget() ?>
</div>
<?= $content; ?>
<?php $this->endBody() ?>
<!--ajax模拟框加载-->
<div class="modal fade" id="ajaxModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <img src="/backend/resources/img/loading.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;加载中... </span>
            </div>
        </div>
    </div>
</div>

<!--ajax大模拟框加载-->
<div class="modal fade" id="ajaxModalLg" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <img src="/backend/resources/img/loading.gif" alt="" class="loading">
                <span> &nbsp;&nbsp;加载中... </span>
            </div>
        </div>
    </div>
</div>

<!--基础ajax弹出框-->
<div class="modal inmodal fade" id="baseModel" tabindex="-1" role="dialog" aria-hidden="true" style="display: none;">
    <div class="modal-dialog modal-lg" style="width: 70%">
        <div class="modal-content">
            <div class="modal-header" style="padding: 15px;text-align: left">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
                基本信息
            </div>
            <div class="modal-body">
                <div class="inlineBlockContainer col3 vAlignTop" id="attachmentList"></div>
                <div class="row text-center" id="loadingAttachment">
                    <span onclick="getAttachment()" class="btn btn-white">点击加载更多</span>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // 小模拟框清除
    $('#ajaxModal').on('hide.bs.modal', function () {
        $(this).removeData("bs.modal");
    });
    // 大模拟框清除
    $('#ajaxModalLg').on('hide.bs.modal', function () {
        $(this).removeData("bs.modal");
    });

    // status => 1:启用;-1禁用;
    function rfStatus(obj){
        var id = $(obj).parent().parent().attr('id');
        var status = 0; self = $(obj);
        if (self.hasClass("btn-primary")){
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
                if (data.code == 200) {
                    if(self.hasClass("btn-primary")){
                        self.removeClass("btn-primary").addClass("btn-default");
                        self.text('禁用');
                    } else {
                        self.removeClass("btn-default").addClass("btn-primary");
                        self.text('启用');
                    }
                } else {
                    rfAffirm(data.message);
                }
            }
        });
    }

    function rfSort(obj){
        var id = $(obj).parent().parent().attr('id');
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
                    if (data.code != 200) {
                        rfAffirm(data.message);
                    }
                }
            });
        }
    }
</script>
</body>
</html>
<?php $this->endPage() ?>


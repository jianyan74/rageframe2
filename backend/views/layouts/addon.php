<?php
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;
use backend\widgets\Alert;
use common\helpers\AddonUrl;
use backend\widgets\menu\AddonLeftWidget;

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
    <body class="hold-transition sidebar-mini fixed">
    <?php $this->beginBody() ?>
    <div class="wrapper-content">
        <section class="content-header">
            <a href="" class="rfHeaderFont">
                <i class="glyphicon glyphicon-refresh"></i> 刷新
            </a>
            <?php if (Yii::$app->request->referrer != Yii::$app->request->hostInfo . Yii::$app->request->getBaseUrl() . '/'){ ?>
                <a href="javascript:history.go(-1)" class="rfHeaderFont">
                    <i class="fa fa-mail-reply"></i> 返回
                </a>
            <?php } ?>
            <?php isset($this->params['breadcrumbs'])
                ? array_unshift($this->params['breadcrumbs'], ['label' => Yii::$app->params['addon']['title']])
                : $this->params['breadcrumbs'][] = ['label' => Yii::$app->params['addon']['title']];
            ?>
            <?= Breadcrumbs::widget([
                'tag' => 'ol',
                'homeLink'=>[
                    'label' => '<i class="fa fa-dashboard"></i>' . Yii::$app->params['adminAcronym'],
                    'url' => "",
                ],
                'encodeLabels' => false,
                'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
            ]) ?>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-2" id="explain">
                    <?= AddonLeftWidget::widget(); ?>
                </div>
                <div class="col-md-10">
                    <?= $content; ?>
                </div>
            </div>
        </section>
        <?= Alert::widget(); ?>
    </div>
    <?php $this->endBody() ?>
    <!--ajax模拟框加载-->
    <div class="modal fade" id="ajaxModal" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-body">
                    <?= Html::img('@web/resources/dist/img/loading.gif', ['class' => 'loading'])?>
                    <img src="" alt="" class="loading">
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
        <div class="modal-dialog modal-lg" style="width: 70%">
            <div class="modal-content">
                <div class="modal-body">
                    <?= Html::img('@web/resources/dist/img/loading.gif', ['class' => 'loading'])?>
                    <span>加载中... </span>
                </div>
            </div>
        </div>
    </div>

    <script>
        $(document).ready(function () {
            var isMobile = "<?php Yii::$app->params['isMobile'];?>";
            if ($(this).width() < 769) {
                isMobile = true;
            }

            // 判断高度
            if ($(window).height() < $('#explain').height() + 60){
                return false;
            }

            $(window).scroll(function () {
                if (isMobile) return;
                var offsetTop = $(window).scrollTop() - 40 + "px";
                $("#explain").animate({ top: offsetTop }, { duration: 600, queue: false });
                if ($(window).scrollTop() < 60){
                    $("#explain").animate({ top:0}, { duration: 600, queue: false });
                }
            });
        });

        $(window).resize(function(){
            // 隐藏菜单
            if ($(this).width() < 769) {
                isMobile = true;
            }
        });

        // 小模拟框清除
        $('#ajaxModal').on('hide.bs.modal', function () {
            $(this).removeData("bs.modal");
        });
        // 大模拟框清除
        $('#ajaxModalLg').on('hide.bs.modal', function () {
            $(this).removeData("bs.modal");
        });
        // 最大模拟框清除
        $('#ajaxModalMax').on('hide.bs.modal', function () {
            $(this).removeData("bs.modal");
        });
        // status => 1:启用;-1禁用;
        function rfStatus(obj){
            var id = $(obj).parent().parent().attr('id');

            if (!id) {
                id = $(obj).parent().parent().attr('data-key');
            }

            var status = 0; self = $(obj);
            if (self.hasClass("btn-success")) {
                status = 1;
            }

            $.ajax({
                type:"get",
                url:"<?= AddonUrl::to(['ajax-update']); ?>",
                dataType: "json",
                data: {id:id,status:status},
                success: function(data){
                    if(data.code == 200) {
                        if(self.hasClass("btn-success")){
                            self.removeClass("btn-success").addClass("btn-default");
                            self.text('禁用');
                        } else {
                            self.removeClass("btn-default").addClass("btn-success");
                            self.text('启用');
                        }
                    }else{
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
            if(isNaN(sort)){
                rfAffirm('排序只能为数字');
                return false;
            }else{
                $.ajax({
                    type:"get",
                    url:"<?= AddonUrl::to(['ajax-update']); ?>",
                    dataType: "json",
                    data: {id:id,sort:sort},
                    success: function(data){
                        if(data.code != 200) {
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
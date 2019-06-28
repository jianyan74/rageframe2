<?php
use common\helpers\Html;
use backend\assets\AppAsset;

AppAsset::register($this);
?>
<?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta name="viewport" content="width=device-width, initial-scale=1.0"<
        <meta name="renderer" content="webkit">
        <?= Html::csrfMetaTags() ?>
        <title><?= Yii::$app->params['adminTitle'];?></title>
        <?= Html::cssFile('@web/resources/dist/css/skins/_all-skins.min.css'); ?>
        <?php $this->head() ?>
    </head>
    <body class="hold-transition skin-blue sidebar-mini fixed">
    <?php $this->beginBody() ?>
    <div class="wrapper">
        <!-- 头部区域 -->
        <?= $this->render('_header', [
            'manager' => Yii::$app->user->identity,
            'menuCates' => Yii::$app->services->sysMenuCate->getOnAuthList()
        ]); ?>
        <!-- 左侧菜单栏 -->
        <?= $this->render('_left', [
            'manager' => Yii::$app->user->identity
        ]); ?>
        <!-- 主体内容区域 -->
        <?= $this->render('_content'); ?>
        <!-- 底部区域 -->
        <?= $this->render('_footer'); ?>
        <!-- 布局样式设置 -->
        <aside class="control-sidebar control-sidebar-dark">
            <div class="tab-content">
                <!-- Home tab content -->
                <div class="tab-pane" id="control-sidebar-home-tab"></div>
            </div>
        </aside>
        <!-- 右侧区域 -->
        <div class="control-sidebar-bg"></div>
    </div>
    <?= Html::jsFile('@web/resources/bower_components/jquery-slimscroll/jquery.slimscroll.min.js'); ?>
    <?= Html::jsFile('@web/resources/dist/js/contabs.js'); ?>
    <script>
        // 配置
        let config = {
            tag: "<?= Yii::$app->debris->config('sys_tags') ?? false; ?>",
            isMobile: "<?= Yii::$app->params['isMobile'] ?? false; ?>",
        };
    </script>
    <?php $this->endBody() ?>
    </body>
    </html>
<?php $this->endPage() ?>
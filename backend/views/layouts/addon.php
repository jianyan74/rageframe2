<?php

use common\helpers\Html;
use yii\widgets\Breadcrumbs;
use backend\assets\AppAsset;
use backend\widgets\Alert;
use common\widgets\menu\AddonLeftWidget;

/* @var $this yii\web\View */

AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="renderer" content="webkit">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body class="hold-transition sidebar-mini fixed">
<?php $this->beginBody() ?>
<div class="wrapper-content">
    <section class="content-header">
        <a href="<?= Yii::$app->request->getUrl(); ?>" class="rfHeaderFont">
            <i class="glyphicon glyphicon-refresh"></i> 刷新
        </a>
        <?php if (Yii::$app->request->referrer != Yii::$app->request->hostInfo . Yii::$app->request->getBaseUrl() . '/') { ?>
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
            'homeLink' => [
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
<script>
    // 配置
    let config = {
        tag: "<?= Yii::$app->debris->backendConfig('sys_tags') ?? false; ?>",
        isMobile: "<?= Yii::$app->params['isMobile'] ?? false; ?>",
        emojiBaseUrl: "<?= Yii::$app->request->baseUrl ?>/resources/img/emoji/",
    };
</script>
<?= $this->render('_footer') ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>


<?php
use addons\RfArticle\frontend\assets\AppAsset;
use common\helpers\Html;
use common\widgets\Alert;
use common\helpers\AddonHelper;
use common\helpers\Url;

$path = AddonHelper::filePath();
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php $this->registerCsrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
    <script src="<?= $path;?>plugin/jquery.min.js"></script>
    <script src="<?= $path;?>plugin/bootstrap/js/bootstrap.min.js"></script>
</head>
<body>
<?php $this->beginBody() ?>
<div class="w_header">
    <div class="container">
        <div class="w_header_top">
            <a href="<?= Url::to(['index/index'])?>" class="w_logo"></a>
            <span class="w_header_nav">
                <ul>
                    <li><a href="<?= Url::to(['index/index'])?>">首页</a></li>
                    <li><a href="<?= Url::to(['index/list'])?>">最新文章</a></li>
                </ul>
            </span>
            <div class="w_search">
                <form action="<?= Url::to(['index/list'])?>" method="get">
                    <div class="w_searchbox">
                        <input type="text" placeholder="关键字查询" name="keyword" value="<?= Yii::$app->request->get('keyword', '')?>"/>
                        <button>搜索</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?= Alert::widget() ?>
<?= $content ?>
<div class="w_foot">
    <div class="w_foot_copyright"><?= Yii::$app->debris->config('web_copyright')?><span></span></div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>

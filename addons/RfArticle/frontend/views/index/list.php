<?php
use common\helpers\AddonHelper;
use common\helpers\Url;

$path = AddonHelper::filePath();
$this->title = '文章列表';
?>

<link rel="stylesheet" type="text/css" href="<?= $path;?>css/article.css"/>

<div class="w_container">
    <div class="container">
        <div class="row w_main_row">
            <ol class="breadcrumb w_breadcrumb">
                <li><a href="<?= Url::to(['index/index'])?>">首页</a></li>
                <li class="active">文章</li>
                <span class="w_navbar_tip">RageFrame，一个基于Yii2高级框架的快速开发应用引擎。</span>
            </ol>
            <div class="col-lg-9 col-md-9 w_main_left">
                <div class="panel panel-default">
                    <div class="panel-body contentList">
                        <?php foreach ($articles as $article){ ?>
                        <div class="panel panel-default w_article_item">
                            <div class="panel-body">
                                <div class="row">
                                    <div class="col-xs-6 col-md-3">
                                        <a href="<?= Url::to(['details', 'id' => $article['id']])?>" class="thumbnail w_thumbnail">
                                            <img src="<?= $article['cover']; ?>" alt="...">
                                        </a>
                                    </div>

                                    <h4 class="media-heading">
                                        <a class="title" href="<?= Url::to(['details', 'id' => $article['id']])?>"><?= $article['title']; ?></a>
                                    </h4>
                                    <p>
                                        <?php if(!empty($article['tags'])){ ?>
                                            <?php foreach ($article['tags'] as $tag){ ?>
                                                <a class="label label-default"><?= $tag['title']?></a>
                                            <?php } ?>
                                        <?php } ?>
                                    </p>
                                    <p class="w_list_overview overView"><?= $article['description']; ?></p>
                                    <p class="count_r">
                                        <span class="count"><i class="glyphicon glyphicon-user"></i><a href="#"><?= $article['author']; ?></a></span>
                                        <span class="count"><i class="glyphicon glyphicon-eye-open"></i>阅读:<?= $article['view']; ?></span>
                                        <span class="count"><i class="glyphicon glyphicon-time"></i><?= Yii::$app->formatter->asDate($article['created_at']); ?></span>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div id="page">
                            <?= \yii\widgets\LinkPager::widget([
                                'pagination' => $pages,
                                'maxButtonCount' => 5,
                            ]);?>
                        </div>
                    </div>
                </div>
            </div>
            <!--获取左侧列表推荐-->
            <?= \common\helpers\Hook::to('RfArticle', ['position' => 2]); ?>
        </div>
    </div>
</div>
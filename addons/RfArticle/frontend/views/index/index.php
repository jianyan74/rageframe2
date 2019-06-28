<?php
use common\helpers\AddonHelper;
use common\helpers\Url;
use common\helpers\Hook;

$path = AddonHelper::filePath();
$this->title = '我的博客';
?>

<div class="w_container">
    <div class="container">
        <div class="row w_main_row">
            <div class="col-lg-9 col-md-9 w_main_left">
                <?= Hook::to('RfArticle', [], 'adv'); ?>
                <div class="panel panel-default contenttop">
                    <a href="javascript:void (0)">
                        <strong>置顶</strong>
                        <h3 class="title">RageFrame</h3>
                        <p class="overView">为二次开发而生，让开发变得更简单</p>
                    </a>
                </div>
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">推荐文章</h3>
                    </div>
                    <div class="panel-body">
                        <!--文章列表开始-->
                        <div class="contentList">
                            <?php foreach ($articles as $article){ ?>
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="contentleft">
                                        <h4><a class="title" href="<?= Url::to(['details', 'id' => $article['id']])?>"><?= $article['title']; ?></a></h4>
                                        <p>
                                            <?php if(!empty($article['tags'])){ ?>
                                                <?php foreach ($article['tags'] as $tag){ ?>
                                                    <a class="label label-default"><?= $tag['title']?></a>
                                                <?php } ?>
                                            <?php } ?>
                                        </p>
                                        <p class="overView"><?= $article['description']; ?></p>
                                        <p>
                                            <span class="count"><i class="glyphicon glyphicon-user"></i><a href="#"><?= $article['author']; ?></a></span>
                                            <span class="count"><i class="glyphicon glyphicon-eye-open"></i>阅读:<?= $article['view']; ?></span>
                                            <span class="count"><i class="glyphicon glyphicon-time"></i><?= Yii::$app->formatter->asDate($article['created_at']); ?></span></p>
                                    </div>
                                    <div class="contentImage">
                                        <!--<img src="img/slider/abs_img_no.jpg"/>-->
                                        <div class="row">
                                            <a href="<?= Url::to(['details', 'id' => $article['id']])?>" class="thumbnail w_thumbnail">
                                                <img src="<?= $article['cover']; ?>" alt="...">
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php } ?>
                        </div>
                        <!--文章列表结束-->
                    </div>
                </div>
            </div>
            <!--获取左侧首页推荐-->
            <?= Hook::to('RfArticle', ['position' => 1]); ?>
        </div>
    </div>
</div>
<?php
use common\helpers\AddonHelper;
use common\helpers\Url;

$path = AddonHelper::filePath();
$this->title = $article['title'];
?>

<link rel="stylesheet" type="text/css" href="<?= $path;?>css/article_detail.css"/>

<div class="w_container">
    <div class="container">
        <div class="row w_main_row">

            <ol class="breadcrumb w_breadcrumb">
                <li><a href="<?= Url::to(['index/index'])?>">首页</a></li>
                <li><a href="<?= Url::to(['index/list'])?>">文章</a></li>
                <li class="active"><?= $article['title']; ?></li>
                <span class="w_navbar_tip">RageFrame，一个基于Yii2高级框架的快速开发应用引擎。</span>
            </ol>
            <div class="col-lg-9 col-md-9 w_main_left">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <h2 class="c_titile"><?= $article['title']; ?></h2>
                        <p class="box_c"><span class="d_time">发布时间：<?= Yii::$app->formatter->asDate($article['created_at']); ?></span><span>编辑：<?= $article['author']; ?></span><span>阅读（<?= $article['view']; ?>）</span></p>
                        <ul class="infos">
                            <?= $article['content']; ?>
                        </ul>
                        <div class="keybq">
                            <p>
                                <span>关键字</span>：
                                <?php if(!empty($article['tags'])){ ?>
                                    <?php foreach ($article['tags'] as $tag){ ?>
                                        <a class="label label-default"><?= $tag['title']?></a>
                                    <?php } ?>
                                <?php } ?>
                            </p>
                        </div>
                        <div class="nextinfo">
                            <?php if(!empty($prev)){ ?><p class="last">上一篇：<a href="<?= Url::to(['index/details', 'id' => $prev['id']])?>"><?= $prev['title']?></a></p><?php } ?>
                            <?php if(!empty($next)){ ?><p class="next">下一篇：<a href="<?= Url::to(['index/details', 'id' => $next['id']])?>"><?= $next['title']?></a></p><?php } ?>
                        </div>
                    </div>
                </div>
            </div>
            <!--获取左侧内页推荐-->
            <?= \common\helpers\Hook::to('RfArticle', ['position' => 4]); ?>
        </div>
    </div>
</div>
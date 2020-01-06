<div class="col-lg-3 col-md-3 w_main_right">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">热门标签</h3>
        </div>
        <div class="panel-body">
            <div class="labelList">
                <?php foreach ($tags as $tag){ ?>
                    <a class="label label-default"><?= $tag['title']?></a>
                <?php } ?>
            </div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading">
            <h3 class="panel-title">文章推荐</h3>
        </div>
        <div class="panel-body">
            <ul class="list-unstyled sidebar">
                <?php foreach ($articles as $article){ ?>
                <li>
                    <a href="<?= \common\helpers\Url::to(['index/details', 'id' => $article['id']])?>"><?= $article['title']; ?></a>
                </li>
                <?php } ?>
            </ul>
        </div>
    </div>
</div>
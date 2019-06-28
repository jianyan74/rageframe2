<!--滚动图开始-->
<div class="panel panel-default">
    <div id="w_carousel" class="carousel slide w_carousel" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <?php foreach ($models as $key => $model){ ?>
                <li data-target="#w_carousel" data-slide-to="<?= $key; ?>" class="<?= $key == 0 ? 'active' : ''; ?>"></li>
            <?php } ?>
        </ol>
        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <?php foreach ($models as $key => $model){ ?>
            <div class="item <?= $key == 0 ? 'active' : ''; ?>">
                <img src="<?= $model['cover'] ?>" alt="...">
                <div class="carousel-caption">
                    <h3><?= $model['title'] ?></h3>
                    <p><?= $model['silder_text'] ?></p>
                </div>
            </div>
            <?php } ?>
        </div>
        <!-- Controls -->
        <a class="left carousel-control" href="#w_carousel" role="button" data-slide="prev">
            <span class="glyphicon glyphicon-chevron-left"></span>
            <span class="sr-only">Previous</span>
        </a>
        <a class="right carousel-control" href="#w_carousel" role="button" data-slide="next">
            <span class="glyphicon glyphicon-chevron-right"></span>
            <span class="sr-only">Next</span>
        </a>
    </div>
</div>
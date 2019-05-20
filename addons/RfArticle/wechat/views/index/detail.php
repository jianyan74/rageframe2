<?php
use common\helpers\Html;

$this->title = '文章详情';
?>

<div id="wrap" class="article-detail">
    <h3 class="title"><?= $model['title']; ?></h3>
    <div class="time">时间：<?= Yii::$app->formatter->asDate($model['created_at']); ?></div>

    <div class="content">
        <img src="<?= $model['cover']; ?>" alt="" />
        <p>
            <?= Html::decode($model['content']); ?>
        </p>
    </div>
</div>
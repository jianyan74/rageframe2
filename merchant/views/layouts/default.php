<?php

use common\helpers\Html;
use merchant\assets\AppAsset;

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
    <?php $this->beginBody() ?>
    <?= $content; ?>
    <script>
        // 配置
        let config = {
            tag: "<?= Yii::$app->debris->merchantConfig('sys_tags') ?? false; ?>",
            isMobile: "<?= Yii::$app->params['isMobile'] ?? false; ?>",
            emojiBaseUrl: "<?= Yii::$app->request->baseUrl ?>/resources/img/emoji/",
        };
    </script>
    <?= $this->render('@backend/views/layouts/_footer') ?>
    <?php $this->endBody() ?>
    </html>
<?php $this->endPage() ?>
<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use common\helpers\Html;

$this->title = $name;
?>

<div class="error-page text-center" style="padding-top: 200px">
    <h1><?= Html::encode($code) ?></h1>
    <h2><?= Html::encode($name) ?></h2>
    <h5><?= nl2br(Html::encode($message)) ?></h5>
    <!-- /.error-content -->
</div>
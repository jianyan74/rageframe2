<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;

$this->title = $name;
?>

<body class="gray-bg">
<div class="wrapper wrapper-content animated fadeInRight">
    <div class="middle-box text-center animated fadeInDown">
        <h1><?= Html::encode($code) ?></h1>
        <h2><?= Html::encode($name) ?></h2>
        <div class="error-desc">
            <?= nl2br(Html::encode($message)) ?>
        </div>
    </div>
</div>
</body>
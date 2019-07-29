<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $user common\models\base\User */

$resetLink = Yii::$app->urlManager->createAbsoluteUrl(['site/reset-password', 'token' => $user->password_reset_token]);
?>

<div class="password-reset">
    <p>您好 <?= Html::encode($user->username) ?>,</p>

    <p>您已经请求了重置密码，可以点击下面的链接来重置密码 </p>

    <p><?= Html::a(Html::encode($resetLink), $resetLink) ?></p>

    <p>如果你没有请求重置密码，请忽略这封邮件.</p>

    <p>在你点击上面链接修改密码之前，你的密码将会保持不变.</p>

</div>
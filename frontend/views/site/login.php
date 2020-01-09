<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use common\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\authclient\widgets\AuthChoice;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Please fill out the following fields to login:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

                <?= $form->field($model, 'username')->textInput(['autofocus' => true]) ?>

                <?= $form->field($model, 'password')->passwordInput() ?>

                <?= $form->field($model, 'rememberMe')->checkbox() ?>

                <div style="color:#999;margin:1em 0">
                    If you forgot your password you can <?= Html::a('reset it', ['site/request-password-reset']) ?>.
                </div>
                <div class="form-group">
                    <?= Html::submitButton('Login', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-lg-7">
            <label class="control-label" style="margin-left: 35px">第三方登录</label>
            <?php $authAuthChoice = AuthChoice::begin([
                'baseAuthUrl' => ['site/auth'],
                'popupMode' => true,
            ]); ?>
            <ul class="auth-clients" style="height: 50px">
                <?php foreach ($authAuthChoice->getClients() as $client): ?>
                    <li>
                        <?= $authAuthChoice->clientLink($client, '', [
                            'class' => 'auth-icon fa fa-2x fa-'.$client->getId(),
                            'style' => 'padding-top:15px',
                        ]) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
            <?php AuthChoice::end(); ?>
        </div>
    </div>
</div>

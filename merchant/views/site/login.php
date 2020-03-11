<?php
$this->title = Yii::$app->params['adminTitle'];

use common\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>

<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <?= Html::encode(Yii::$app->params['adminTitle']); ?>
    </div>
    <!-- /.login-logo -->
    <div class="login-box-body">
        <p class="login-box-msg">欢迎登录</p>
        <?php $form = ActiveForm::begin([
                'id' => 'login-form'
        ]); ?>
        <?= $form->field($model, 'username', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span></div>{hint}{error}'
        ])->textInput(['placeholder' => '用户名'])->label(false); ?>
        <?= $form->field($model, 'password', [
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span></div>{hint}{error}'
        ])->passwordInput(['placeholder' => '密码'])->label(false); ?>
        <?php if ($model->scenario == 'captchaRequired') { ?>
            <?= $form->field($model,'verifyCode')->widget(Captcha::class,[
                'template' => '<div class="row"><div class="col-sm-7">{input}</div><div class="col-sm-5">{image}</div></div>',
                'imageOptions' => [
                    'alt' => '点击换图',
                    'title' => '点击换图',
                    'style' => 'cursor:pointer'
                ],
                'options' => [
                    'class' => 'form-control',
                    'placeholder' => '验证码',
                ],
            ])->label(false); ?>
        <?php } ?>
        <?= $form->field($model, 'rememberMe')->checkbox() ?>
        <div class="form-group">
            <?= Html::submitButton('立即登录', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
        <?php if (!empty(Yii::$app->debris->backendConfig('merchant_register_is_open'))) { ?>
            <div class="social-auth-links text-center">还没有有帐号？<?= Html::a('立即注册', ['register']); ?></div>
        <?php } ?>
        <div class="social-auth-links text-center">
            <p><?= Html::encode(Yii::$app->debris->backendConfig('web_copyright')); ?></p>
        </div>
    </div>
    <!-- /.login-box-body -->
</div>
<!-- /.login-box -->
</body>
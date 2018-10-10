<?php
$this->title = Yii::$app->params['adminTitle'];

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\captcha\Captcha;
?>

<?= Html::cssFile('@resources/css/login.css'); ?>

<script>
    if (window.top !== window.self) {
        window.top.location = window.location;
    }
</script>

<body class="signin">
<div class="signinpanel">
    <div class="row">
        <div class="col-sm-3"></div>
        <div class="col-sm-6">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
            <p class="no-margins">欢迎登录 <?= Yii::$app->params['adminTitle']; ?></p>
            <?= $form->field($model, 'username')->textInput(['autofocus' => true,'placeholder' => '用户名','class' => 'form-control uname'])->label(false) ?>
            <?= $form->field($model, 'password')->passwordInput(['placeholder' => '密码','class' => 'form-control pword m-b'])->label(false) ?>

            <?php if ($model->scenario == 'captchaRequired'){ ?>
                <?= $form->field($model,'verifyCode')->widget(Captcha::className(),[
                    'template' => '<div class="row"><div class="col-sm-7">{input}</div><div class="col-sm-5">{image}</div></div>',
                    'imageOptions' => [
                        'alt' => '点击换图',
                        'title' => '点击换图',
                        'style' => 'cursor:pointer'
                    ],
                    'options' => [
                        'class' => 'form-control verifyCode',
                        'placeholder' => '验证码',
                    ],
                ])->label(false)?>
            <?php } ?>
            <?php
                $field = $form->field($model, 'rememberMe',['labelOptions' => ['class' => 'verifyCode']])->checkbox();
                $field->label();
                $field->error();
            ?>
            <div class="form-group text-left">
                <div class="checkbox i-checks">
                    <label class="no-padding">
                        <?= $field->parts['{input}']; ?><i></i> <?= $field->parts['{labelTitle}'];?>
                    </label>
                </div>
            </div>
            <div class="form-group">
                <?= Html::submitButton('立即登录', ['class' => 'btn btn-primary btn-block', 'name' => 'login-button']) ?>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
        <div class="col-sm-3"></div>
    </div>
    <div class="signup-footer">
        <div class="text-center">
            <?= Yii::$app->debris->config('web_copyright'); ?>
        </div>
    </div>
</div>

<script>
    $(document).ready(function(){
        $(".i-checks").iCheck({
            checkboxClass   :"icheckbox_square-green",
            radioClass      :"iradio_square-green",
            increaseArea    : '20%' // optional
        })
    });
</script>
<?php
use common\helpers\Html;

use yii\widgets\ActiveForm;

$this->title = '商家入驻';
?>

<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
        <?= Html::a('<b>'.Html::encode(Yii::$app->debris->config('web_site_title')).'</b>-'.Html::encode($this->title),['/']);?>
    </div>

    <div class="register-box-body">
        <p class="login-box-msg">Register a new membership</p>

        <?php $form = ActiveForm::begin([
            'id' => 'register-form'
        ]);?>

        <?= $form->field($model, 'title',[
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-home form-control-feedback"></span></div>{hint}{error}'
        ])->textInput(['placeholder' => '商户名称'])->label(false);?>
        <?= $form->field($model,'category_id')->dropDownList($cate,['prompt'=>'请选择商家分类'])->label(false);?>
        <?= $form->field($model, 'username',[
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-user form-control-feedback"></span></div>{hint}{error}'
        ])->textInput(['placeholder' => '用户名'])->label(false);?>
        <?= $form->field($model, 'mobile',[
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-phone form-control-feedback"></span></div>{hint}{error}'
        ])->textInput(['placeholder' => '手机号码'])->label(false);?>
        <?= $form->field($model, 'email',[
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-envelope form-control-feedback"></span></div>{hint}{error}'
        ])->textInput(['placeholder' => '电子邮箱'])->label(false);?>
        <?= $form->field($model, 'password',[
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-lock form-control-feedback"></span></div>{hint}{error}'
        ])->passwordInput(['placeholder' => '用户密码'])->label(false);?>
        <?= $form->field($model, 're_pass',[
            'template' => '<div class="form-group has-feedback">{input}<span class="glyphicon glyphicon-log-in form-control-feedback"></span></div>{hint}{error}'
        ])->passwordInput(['placeholder' => '确认密码'])->label(false);?>

        <div class="row">
            <div class="col-xs-8">
                <div class="checkbox icheck">
                    <label class="loginform-rememberme">
                        <input type="checkbox"> 我同意 <a href="#">商家入驻协议</a>
                    </label>
                </div>
            </div>

            <div class="col-xs-12">
                <?= Html::submitButton('注&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;册',['class' =>'btn btn-primary btn-block']);?>
            </div>

        </div>

        <?php ActiveForm::end();?>
        <div class="social-auth-links text-center">

        </div>

        已有帐号？<?= Html::a('点此登录',['login']);?>
    </div>
    <!-- /.form-box -->
</div>



</body>

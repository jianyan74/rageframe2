<?php
use common\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '关注/默认回复';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['rule/index'])?>"> 关键字自动回复</a></li>
                <li><a href="<?= Url::to(['setting/special-message'])?>"> 非文字自动回复</a></li>
                <li class="active"><a href="<?= Url::to(['reply-default/index'])?>"> 关注/默认回复</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane rf-auto">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="col-sm-12">
                        <?= $form->field($model, 'follow_content')->widget(\kartik\select2\Select2::class, [
                            'data' => $keyword,
                            'options' => ['placeholder' => '请选择'],
                            'pluginOptions' => [
                                'allowClear' => false,
                                'tags' => true,
                            ],
                        ])->hint('注意：这里是自动回复设置的关键字，设置用户添加公众帐号好友时，发送的欢迎信息。');?>

                        <?= $form->field($model, 'default_content')->widget(\kartik\select2\Select2::class, [
                            'data' => $keyword,
                            'options' => ['placeholder' => '请选择'],
                            'pluginOptions' => [
                                'allowClear' => true,
                                'tags' => true,
                            ],
                        ])->hint('注意：这里是自动回复设置的关键字，当系统不知道该如何回复粉丝的消息时，默认发送的内容。');?>
                    </div>
                    <div class="col-sm-12 text-center">
                        <button class="btn btn-primary" type="submit">保存</button>
                    </div>
                    <?php ActiveForm::end(); ?>
                </div>
            </div>
        </div>
    </div>
</div>
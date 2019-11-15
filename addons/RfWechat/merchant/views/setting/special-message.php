<?php

use common\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '非文字回复 ';
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['rule/index']); ?>"> 关键字自动回复</a></li>
                <li class="active"><a href="<?= Url::to(['setting/special-message']); ?>"> 非文字自动回复</a></li>
                <li><a href="<?= Url::to(['reply-default/index']); ?>"> 关注/默认回复</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane rf-auto">
                    <?php $form = ActiveForm::begin(); ?>
                    <div class="col-sm-12">
                        <?php foreach ($list as $key => $row){ ?>
                            <div class="form-group">
                                <label class="control-label"><?= $row['title'] ?></label>
                                <div>
                                    <label><input name="setting[<?= $key ?>][type]" value="1" type="radio" <?= $row['type'] == 1 ? 'checked' : '' ?> onclick="$(this).parent().parent().next().show();$(this).parent().parent().next().next().hide();"> 关键字</label>
                                    <label><input name="setting[<?= $key ?>][type]" value="2" type="radio" <?= $row['type'] == 1 ? '' : 'checked' ?> onclick="$(this).parent().parent().next().hide();$(this).parent().parent().next().next().show();"> 模块</label>
                                </div>
                                <div style="display: <?= $row['type'] == 1 ? 'block' : 'none' ?>">
                                    <input class="form-control" name="setting[<?= $key ?>][content]" value="<?= $row['content'] ?>" type="text">
                                </div>
                                <div style="display: <?= $row['type'] == 1 ? "none" : "block" ?>">
                                    <select name="setting[<?= $key ?>][selected]" class="form-control">
                                        <option value="" selected>不处理(使用系统默认回复)</option>
                                        <!--<option value="custom">多客服转接</option>-->
                                        <?php foreach ($row['module'] as $k => $item){ ?>
                                            <option value="<?= $k ?>" <?php if(isset($row['selected']) && $k == $row['selected']){?>selected<?php } ?>><?= $item ?></option>
                                        <?php } ?>
                                    </select>
                                </div>
                                <div class="hint-block">如果【<?= $row['title'] ?>】到达时, 将会采用选中的模块来处理. 如果选择"不处理", 那么这个消息将会使用系统默认回复来回复</div>
                                <div class="help-block"></div>
                            </div>
                        <?php } ?>
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
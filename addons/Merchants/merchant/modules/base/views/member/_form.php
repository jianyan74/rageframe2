<?php

use yii\widgets\ActiveForm;
use common\enums\GenderEnum;

$actionLog = Yii::$app->services->actionLog->findByAppId(Yii::$app->id, $model['id'], 10);

?>

<?php $form = ActiveForm::begin(); ?>
<div class="row">
    <div class="col-sm-3">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black"
                 style="background: url('<?= Yii::getAlias('@web'); ?>/resources/img/photo1.png') center center;">
                <h3 class="widget-user-username"><?= $model->username; ?></h3>
                <h5 class="widget-user-desc">最近登录：<?= Yii::$app->formatter->asDatetime($model->last_time) ?></h5>
                <h5>最近登录IP：<?= $model->last_ip ?></h5>
            </div>
            <?php if ($actionLog) { ?>
                <div class="box-body">
                    <div class="col-md-12 changelog-info">
                        <ul class="time-line">
                            <?php foreach ($actionLog as $item) { ?>
                                <li>
                                    <time><?= Yii::$app->formatter->asDatetime($item['created_at']) ?></time>
                                    <h5><?= $item['remark'] ?></h5>
                                </li>
                            <?php } ?>
                        </ul>
                        <!-- /.widget-user -->
                    </div>
                </div>
            <?php } else { ?>
                <div class="box-body">
                    <div class="col-md-12 changelog-info">
                        暂无行为记录...
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
    <div class="col-sm-9">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="#base" data-toggle="tab" aria-expanded="true">基本信息</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane active" id="base">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'head_portrait')->widget(\common\widgets\cropper\Cropper::class, [
                            // 'theme' => 'default',
                            'config' => [
                                // 可设置自己的上传地址, 不设置则默认地址
                                // 'server' => '',
                            ],
                        ]); ?>
                        <?= $form->field($model, 'realname')->textInput() ?>
                        <?= $form->field($model, 'gender')->radioList(GenderEnum::getMap()) ?>
                        <?= $form->field($model, 'mobile')->textInput() ?>
                        <?= \common\widgets\provinces\Provinces::widget([
                            'form' => $form,
                            'model' => $model,
                            'template' => 'short',
                            'provincesName' => 'province_id',// 省字段名
                            'cityName' => 'city_id',// 市字段名
                            'areaName' => 'area_id',// 区字段名
                        ]); ?>
                        <?= $form->field($model, 'email')->textInput() ?>
                        <?= $form->field($model, 'birthday')->widget('kartik\date\DatePicker', [
                            'language' => 'zh-CN',
                            'layout' => '{picker}{input}',
                            'pluginOptions' => [
                                'format' => 'yyyy-mm-dd',
                                'todayHighlight' => true,// 今日高亮
                                'autoclose' => true,// 选择后自动关闭
                                'todayBtn' => true,// 今日按钮显示
                            ],
                            'options' => [
                                'class' => 'form-control no_bor',
                            ]
                        ]); ?>
                        <?= $form->field($model, 'address')->textarea() ?>
                    </div>
                </div>
                <div class="tab-pane" id="oauth">
                    <div class="col-lg-12">
                        <?= $form->field($model, 'dingtalk_robot_token')->textInput()->hint('配置后并开启钉钉消息提醒和定时任务即可进行提醒，'
                            . '<a href="https://ding-doc.dingtalk.com/doc#/serverapi2/qf2nxq" class="blue" target="_blank">文档</a>') ?>
                    </div>
                </div>

                <div class="box-footer text-center">
                    <button class="btn btn-primary" type="submit" onclick="sendForm()">保存</button>
                    <?= $backBtn ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
    // 提交表单时候触发
    function sendForm() {
        var status = "<?= Yii::$app->user->id == $model->id ? true : false;?>";
        if (status) {
            var src = $('input[name="Manager[head_portrait]"]').val();
            if (src) {
                $('.head_portrait', window.parent.document).attr('src', src);
            }
        }
    }
</script>
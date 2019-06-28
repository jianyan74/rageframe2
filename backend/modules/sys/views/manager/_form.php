<?php
use common\helpers\Url;
use yii\widgets\ActiveForm;
use common\helpers\Html;
use common\enums\GenderEnum;
use common\helpers\ImageHelper;

$actionLog = Yii::$app->services->sysActionLog->findByManagerId($model['id']);
?>

<?php $form = ActiveForm::begin([
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
    ]
]); ?>
<div class="row">
    <div class="col-sm-3">
        <!-- Widget: user widget style 1 -->
        <div class="box box-widget widget-user">
            <!-- Add the bg color to the header using any of the bg-* classes -->
            <div class="widget-user-header bg-black" style="background: url('<?= Yii::getAlias('@web'); ?>/resources/dist/img/photo1.png') center center;">
                <h3 class="widget-user-username"><?= $model->username; ?></h3>
                <h5 class="widget-user-desc"><?= Yii::$app->formatter->asDatetime($model->last_time) ?></h5>
                <h5><?= $model->last_ip ?></h5>
            </div>
        </div>
        <?php if($actionLog) {?>
            <div class="box ">
                <div class="box-body">
                    <div class="col-md-12 changelog-info">
                        <ul class="time-line">
                            <?php foreach ($actionLog as $item){ ?>
                                <li>
                                    <time><?= date('y-m-d H:i:s', $item['created_at']) ?></time>
                                    <h5><?= $item['remark'] ?></h5>
                                </li>
                            <?php } ?>
                        </ul>
                        <!-- /.widget-user -->
                    </div>
                    <div class="pull-right">
                        <a href="<?= Url::to(['/sys/action-log/index']); ?>" class="openContab">行为日志</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
    <div class="col-sm-9">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="box-body">
                <?= $form->field($model, 'head_portrait')->widget(\backend\widgets\cropper\Cropper::class, [
                    // 'theme' => 'default',
                    'config' => [
                        // 可设置自己的上传地址, 不设置则默认地址
                        // 'server' => '',
                        'circle' => true
                    ],
                ]); ?>
                <?= $form->field($model, 'realname')->textInput() ?>
                <?= $form->field($model, 'gender')->radioList(GenderEnum::$listExplain) ?>
                <?= $form->field($model, 'mobile')->textInput() ?>
                <?= \backend\widgets\provinces\Provinces::widget([
                    'form' => $form,
                    'model' => $model,
                    'provincesName' => 'province_id',// 省字段名
                    'cityName' => 'city_id',// 市字段名
                    'areaName' => 'area_id',// 区字段名
                ]); ?>
                <?= $form->field($model, 'email')->textInput() ?>
                <?= $form->field($model,'birthday')->widget('kartik\date\DatePicker', [
                    'language'  => 'zh-CN',
                    'layout'=>'{picker}{input}',
                    'pluginOptions' => [
                        'format' => 'yyyy-mm-dd',
                        'todayHighlight' => true,// 今日高亮
                        'autoclose' => true,// 选择后自动关闭
                        'todayBtn' => true,// 今日按钮显示
                    ],
                    'options'=>[
                        'class' => 'form-control no_bor',
                    ]
                ]); ?>
                <?= $form->field($model, 'address')->textarea() ?>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit" onclick="sendForm()">保存</button>
                <?= $backBtn ?>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script type="text/javascript">
    // 提交表单时候触发
    function sendForm(){
        var status = "<?= Yii::$app->user->id == $model->id ? true : false ;?>";
        if(status){
            var src = $('input[name="Manager[head_portrait]"]').val();
            if(src){
                $('.head_portrait',window.parent.document).attr('src',src);
            }
        }
    }
</script>
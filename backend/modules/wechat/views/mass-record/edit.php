<?php
use common\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\datetime\DateTimePicker;
use backend\widgets\selector\Select;
use common\helpers\ArrayHelper;
use common\enums\StatusEnum;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '定时群发', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<?php $form = ActiveForm::begin([
    'id' => 'massRecord',
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['edit', 'id' => $model['id']]),
]); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <div class="box-body">
                <div class="col-lg-12">
                    <?= $form->field($model, 'tag_id')->dropDownList(ArrayHelper::merge([-1 => '全部粉丝'], ArrayHelper::map($tags, 'id', 'name'))) ?>
                    <?= $form->field($model, 'send_type')->radioList(['1' => '立即发送','2' => '定时发送']) ?>
                    <?= $form->field($model, 'send_time', [
                        'options' => [
                            'class' => $model->send_time == 1 ? 'hide' : '',
                            'id' => 'send_time'
                        ]
                    ])->widget(DateTimePicker::class, [
                        'language' => 'zh-CN',
                        'options' => [
                            'value' => $model->isNewRecord ? date('Y-m-d H:i', strtotime(date('Y-m-d H:i'))) : date('Y-m-d H:i', $model->send_time),
                        ],
                        'pluginOptions' => [
                            'format' => 'yyyy-mm-dd hh:ii',
                            'todayHighlight' => true,//今日高亮
                            'autoclose' => true,//选择后自动关闭
                            'todayBtn' => true,//今日按钮显示
                        ]
                    ])->hint('注意：需要开启定时任务');?>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="col-sm-12">
    <div class="nav-tabs-custom">
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab-1" aria-expanded="true" class="text" onclick="setType('text')">内容</a></li>
            <li><a data-toggle="tab" href="#tab-2" aria-expanded="false" class="image" onclick="setType('image')">图片</a></li>
            <li><a data-toggle="tab" href="#tab-3" aria-expanded="false" class="news" onclick="setType('news')">图文</a></li>
            <li><a data-toggle="tab" href="#tab-4" aria-expanded="false" class="video" onclick="setType('video')">视频</a></li>
            <li><a data-toggle="tab" href="#tab-5" aria-expanded="false" class="voice" onclick="setType('voice')">语音</a></li>
        </ul>
        <div class="tab-content">
            <div id="tab-1" class="tab-pane active">
                <div class="panel-body">
                    <?= $form->field($model, 'text')->textarea([
                        'id' => 'content'
                    ])->label(false) ?>
                </div>
            </div>
            <div id="tab-2" class="tab-pane">
                <div class="panel-body">
                    <?= $form->field($model, 'image')->widget(Select::class, [
                        'type' => 'image',
                    ])->label(false) ?>
                </div>
            </div>
            <div id="tab-3" class="tab-pane">
                <div class="panel-body">
                    <?= $form->field($model, 'news')->widget(Select::class, [
                        'type' => 'news',
                        'block' => '由于微信限制，自动回复只能回复一条图文信息，如果有多条图文，默认选择第一条图文',
                    ])->label(false) ?>
                </div>
            </div>
            <div id="tab-4" class="tab-pane">
                <div class="panel-body">
                    <?= $form->field($model, 'video')->widget(Select::class, [
                        'type' => 'video',
                    ])->label(false) ?>
                </div>
            </div>
            <div id="tab-5" class="tab-pane">
                <div class="panel-body">
                    <?= $form->field($model, 'voice')->widget(Select::class, [
                        'type' => 'voice',
                    ])->label(false) ?>
                </div>
            </div>
            <?php if($model->send_status == StatusEnum::DELETE){ ?>
                <div class="alert-warning alert">
                    <?= Html::decode($model->error_content) ?>
                </div>
            <?php } ?>
        </div>
    </div>
</div>

<div class="hidden">
    <?= $form->field($model, 'module')->textInput(['id' => 'module']) ?>
</div>

<div class="box-footer text-center">
    <?php if($model->send_status == StatusEnum::DISABLED){ ?>
        <span class="btn btn-primary" onclick="beforSubmit()">保存</span>
    <?php } ?>
    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
</div>
<?php ActiveForm::end(); ?>
<script>
    var module = '<?= $model->module; ?>';
    var type = 'text';// 1:文字;2:图片;3:图文;4:视频;5:音频;
    // 设置类型
    function setType(num) {
        type = num;
    }

    $(document).ready(function(){
        if (module) {
            $('.' + module).trigger('click')
        }
    })

    function beforSubmit() {
        var val = description = title = '';
        var id = "<?= $model['id']; ?>";
        $('#module').val(type);
        $('#massRecord').submit();
    }

    $("input[name='SendForm[send_type]']").click(function(){
        var val = $(this).val();
        if (val == 1) {
            $('#send_time').addClass('hide');
        } else {
            $('#send_time').removeClass('hide');
        }
    })
</script>
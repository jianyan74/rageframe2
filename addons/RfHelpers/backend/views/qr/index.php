<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = '二维码生成';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?php $form = ActiveForm::begin([
    'id' => 'qr',
    'fieldConfig' => [
        'template' => "<div class='col-sm-3 text-right'>{label}</div><div class='col-sm-9'>{input}\n{hint}\n{error}</div>",
    ]
]); ?>
<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= Html::encode($this->title) ?></h3>
            </div>
            <div class="box-body">
                <div class="col-sm-12">
                    <div class="col-md-6">
                        <?= $form->field($model, 'content')->textInput(); ?>
                        <?= $form->field($model, 'size')->textInput(); ?>
                        <?= $form->field($model, 'margin')->textInput(); ?>
                        <?= $form->field($model, 'error_correction_level')->radioList([
                            'low' => '低',
                            'medium' => '中等',
                            'quartile' => '高',
                            'high' => '超高',
                        ]); ?>
                        <?= $form->field($model, 'foreground')->widget(kartik\color\ColorInput::class, [
                            'options' => [
                                'placeholder' => '请选择颜色',
                                'readonly' => true
                            ],
                        ]);?>
                        <?= $form->field($model, 'background')->widget(kartik\color\ColorInput::class, [
                            'options' => [
                                'placeholder' => '请选择颜色',
                                'readonly' => true
                            ],
                        ]);?>
                        <div class="col-md-3"></div>
                        <div class="col-md-9">
                            <span class="btn btn-primary" id="change">立即创建</span>
                            <input type="reset" name="button" class="btn btn-white" value="重置" />
                        </div>
                    </div>
                    <div class="col-md-6">
                        <?= $form->field($model, 'logo')->widget('common\widgets\webuploader\Files', [
                            'themeConfig' => [
                                'select' => false,// 选择在线图片
                            ],
                            'config' => [
                                'pick' => [
                                    'multiple' => false,
                                ],
                                'accept' => [
                                    'extensions' => ['png', 'jpeg', 'jpg'],
                                ],
                                'formData' => [
                                    'drive' => 'local',
                                ],
                                'fileSingleSizeLimit' => 1024 * 500,// 图片大小限制
                                'independentUrl' => true,
                            ]
                        ])->hint('只支持 png/jpeg/jpg 格式,大小不超过为500K'); ?>
                        <?= $form->field($model, 'logo_size')->textInput(); ?>
                        <?= $form->field($model, 'label')->textInput(); ?>
                        <?= $form->field($model, 'label_size')->textInput(); ?>
                        <?= $form->field($model, 'label_location')->radioList([
                            'left' => '左边',
                            'center' => '居中',
                            'right' => '右边',
                        ]); ?>
                    </div>
                    <div class="col-md-12">
                        <div class="form-group text-center">
                            <div class="row hide">
                                <img src="<?= Url::to(['qr', 'shortUrl' => Yii::$app->request->hostInfo]) ?>" id="qrsrc">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end(); ?>

<script>
    $(document).ready(function () {
        // create();
    });

    $('#change').click(function () {
        create();
        $("#qrsrc").parent().removeClass('hide');
    });

    function create() {
        var data = $('#qr').serializeArray();
        var len = data.length;
        var str = '';

        for (let i = 0; i < len; i++) {
            str += data[i]['name'] + '=' + data[i]['value'] + '&'
        }

        str = str.replace(/\#/g, "%23"); //"#"
        var img_url = "<?= Url::to(['create']) ?>";
        $('#qrsrc').attr('src', img_url + '?' + str);
    }
</script>

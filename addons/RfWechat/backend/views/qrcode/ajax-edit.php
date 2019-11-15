<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;

?>
<?php $form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
]); ?>
<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">关闭</span></button>
    <h4 class="modal-title">创建二维码</h4>
</div>

<div class="modal-body">
    <?= $form->field($model, 'name')->textInput([
        'readonly' => $model->isNewRecord ? false : true
    ]) ?>
    <div class="row <?= !$model->isNewRecord ? 'hide' : '' ?>">
        <div class="col-md-4">
            <?= $form->field($model, 'model')->dropDownList([1 => '临时',2 => '永久'],['onclick' => []]) ?>
        </div>
        <div class="col-md-8">
            <div id="model1">
                <?= $form->field($model, 'expire_seconds')->textInput()->hint('临时二维码过期时间, 最大为30天（2592000秒）')?>
            </div>
            <div id="model2" style="display: none;">
                <?= $form->field($model, 'scene_str')->textInput()->hint('场景值不能为空,并且只能为字符串')?>
            </div>
        </div>
    </div>
    <div class="form-group">
        <div class="hint-block">目前有2种类型的二维码, 分别是临时二维码和永久二维码, 前者有过期时间, 最大为30天（2592000秒）, 但能够生成较多数量, 后者无过期时间, 数量较少(目前参数只支持1--100000).
        </div>
    </div>
    <?= $form->field($model, 'keyword')->textInput()->hint('二维码对应关键字, 用户扫描后系统将通过场景ID返回关键字到平台处理.')?>
</div>

<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>
<?php ActiveForm::end(); ?>

<script>
    $("select[name='Qrcode[model]']").change(function(){
        var showId = $(this).val();
        var hideId = showId == 1 ? 2 : 1;
        $('#model' + hideId).hide();
        $('#model' + showId).show();
    })
</script>
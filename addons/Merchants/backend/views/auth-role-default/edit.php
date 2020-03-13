<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;

$this->title = '默认角色';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>
<div class="alert alert-info alert-dismissible">
    <h4><i class="icon fa fa-info"></i> 注意!</h4>
    商户信息创建后会自动创建一个角色，其权限为当前的默认角色权限，如果不需要请禁用默认角色
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">默认角色</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ],
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'title')->textInput(); ?>
                <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
                <?= $form->field($model, 'sort')->textInput(); ?>
                <div class="col-sm-2"></div>
                <div class="col-sm-5">
                    <?= \common\widgets\jstree\JsTree::widget([
                        'name' => "userTree",
                        'defaultData' => $defaultFormAuth,
                        'selectIds' => $defaultCheckIds,
                    ]) ?>
                </div>
                <div class="col-sm-5">
                    <?= \common\widgets\jstree\JsTree::widget([
                        'name' => "plugTree",
                        'defaultData' => $addonsFormAuth,
                        'selectIds' => $addonsCheckIds,
                    ]) ?>
                </div>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary" type="button" onclick="submitForm()">保存</button>
                    <span class="btn btn-white" onclick="history.go(-1)">返回</span>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script>
    // 提交表单
    function submitForm() {

        var userTreeIds = getCheckTreeIds("userTree");
        var plugTreeIds = getCheckTreeIds("plugTree");

        rfAffirm('保存中...');

        $.ajax({
            type: "post",
            url: "<?= Url::to(['edit', 'id' => $model->id, 'merchant_id' => $merchant_id])?>",
            dataType: "json",
            data: {
                id: '<?= $model['id']?>',
                pid: $("#authrole-pid").val(),
                sort: $("#authrole-sort").val(),
                status: $("input[name='AuthRole[status]']:checked").val(),
                title: $("#authrole-title").val(),
                userTreeIds: userTreeIds,
                plugTreeIds: plugTreeIds
            },
            success: function (data) {
                if (parseInt(data.code) === 200) {
                    window.location = "<?= Url::to(['edit', 'merchant_id' => $merchant_id])?>";
                } else {
                    rfError(data.message);
                }
            }
        });
    }
</script>
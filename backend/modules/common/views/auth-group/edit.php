<?php
use yii\widgets\ActiveForm;
use common\helpers\Url;
use common\enums\StatusEnum;
use common\enums\WhetherEnum;
use common\helpers\Html;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => '分组管理', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title">基本信息</h3>
            </div>
            <?php $form = ActiveForm::begin([
                'fieldConfig' => [
                    'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
                ]
            ]); ?>
            <div class="box-body">
                <?= $form->field($model, 'title')->textInput(); ?>
                <?= $form->field($model, 'is_free')->radioList(WhetherEnum::getMap());?>
                <?= $form->field($model, 'price')->input('number');?>
                <?= $form->field($model, 'count_unit')->dropDownList([]);?>
                <?= $form->field($model, 'status')->radioList(StatusEnum::getMap()); ?>
                <?= $form->field($model, 'sort')->textInput(); ?>
                <div class="col-sm-2"></div>
                <div class="col-sm-5">
                    <?= \common\widgets\jstree\JsTree::widget([
                        'name' => "userTree",
                        'defaultData' => $defaultFormAuth,
                        'selectIds' => $defaultCheckIds,
                    ])?>
                </div>
                <div class="col-sm-5">
                    <?= \common\widgets\jstree\JsTree::widget([
                        'name' => "plugTree",
                        'defaultData' => $addonsFormAuth,
                        'selectIds' => $addonsCheckIds,
                    ])?>
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
    function submitForm(){
        var userTreeIds = getCheckTreeIds("userTree");
        var plugTreeIds = getCheckTreeIds("plugTree");

        rfAffirm('保存中...');

        $.ajax({
            type :"post",
            url : "<?= Url::to(['edit', 'id' => $model->id])?>",
            dataType : "json",
            data : {
                id : '<?= $model['id']?>',
                title : $("#authgroup-title").val(),
                is_free : $("#authgroup-is_free input[name='AuthGroup[is_free]']").val(),
                price : $("#authgroup-price").val(),
                count_unit : $("#authgroup-count_unit").val(),
                sort : $("#authgroup-sort").val(),
                status : $("#authgroup-status input[name='AuthGroup[status]']").val(),
                userTreeIds : userTreeIds,
                plugTreeIds : plugTreeIds
            },
            success : function(data){
                if (parseInt(data.code) === 200) {
                    window.location = "<?= Url::to(['index'])?>";
                } else {
                    rfError(data.message);
                }
            }
        });
    }
</script>
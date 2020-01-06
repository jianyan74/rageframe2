<?php
use yii\widgets\ActiveForm;
use common\helpers\Html;

$this->title = 'Excel导入权限';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <div class="box-header with-border">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="pull-right">
                    <?= Html::linkButton(['download', 'type' => 'default'], '<i class="fa fa-cloud-download"></i> 权限默认表格下载'); ?>
                    <?= Html::linkButton(['download', 'type' => 'merchant'], '<i class="fa fa-cloud-download"></i> 权限商家表格下载'); ?>
                </div>
            </div>
            <?php $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
            ]); ?>
            <div class="box-body">
                <div class="form-group field-provincejob-max_level">
                    <label class="control-label" for="provincejob-max_level">权限类别</label>
                    <?= Html::dropDownList('app_id', '', \common\enums\AppEnum::getMap(), [
                        'class' => 'form-control',
                    ]) ?>
                    <div class="hint-block">请在项目初始化的时候才使用，不然会清空所有已有的权限</div>
                    <div class="help-block"></div>
                </div>
                <div class="form-group">
                    <div class="input-group m-b">
                        <input id="excel-file" type="file" name="excelFile" style="display:none">
                        <input type="text" class="form-control" id="fileName" name="fileName" readonly>
                        <span class="input-group-btn">
                            <a class="btn btn-white" onclick="$('#excel-file').click();">选择文件</a>
                        </span>
                    </div>
                </div>
            </div>
            <div class="box-footer text-center">
                <button class="btn btn-primary" type="submit">保存</button>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('input[id=excel-file]').change(function() {
        $('#fileName').val($(this).val());
    });
</script>

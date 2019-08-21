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
                    <?= Html::linkButton(['download'], '权限默认表格下载'); ?>
                </div>
            </div>
            <?php $form = ActiveForm::begin([
                'options' => [
                    'enctype' => 'multipart/form-data'
                ]
            ]); ?>
            <div class="box-body">
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

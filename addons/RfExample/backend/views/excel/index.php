<?php
use yii\widgets\ActiveForm;

$this->title = 'Excel上传';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-sm-12">
        <div class="ibox float-e-margins">
            <div class="ibox-title">
                <h5>基本信息</h5>
            </div>
            <div class="ibox-content">
                <div class="col-sm-12">
                    <?php $form = ActiveForm::begin([
                        'options' => [
                            'enctype' => 'multipart/form-data'
                        ]
                    ]); ?>
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
                <div class="form-group">
                    <div class="col-sm-12 text-center">
                        <div class="hr-line-dashed"></div>
                        <button class="btn btn-primary" type="submit">保存</button>
                    </div>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('input[id=excel-file]').change(function() {
        $('#fileName').val($(this).val());
    });
</script>

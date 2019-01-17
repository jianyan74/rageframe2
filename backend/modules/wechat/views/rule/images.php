<?php
use yii\helpers\Url;

?>

<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">回复内容</h4>
    </div>
    <div class="box-body">
        <div class="col-lg-12">
            <?= \backend\widgets\wechatselectattachment\Select::widget([
                'name' => 'ReplyImages[media_id]',
                'value' => $moduleModel->media_id,
                'type' => 'image',
                'label' => '图片',
            ])?>
        </div>
        <div class="form-group">　
            <div class="col-sm-12 text-center">
                <span class="btn btn-primary" onclick="beforSubmit()">保存</span>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>

<script>
    function beforSubmit() {
        var val =  $('input[name="ReplyImages[media_id]"]').val();
        if (!val){
            rfAffirm('请选择图片');
            return false;
        }

        $('#w0').submit();
    }
</script>
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
                'type' => 'news',
                'label' => '图文',
                'block' => '由于微信限制，自动回复只能回复一条图文信息，如果有多条图文，默认选择第一条图文',
                'name' => 'ReplyNews[attachment_id]',
                'value' => $moduleModel->attachment_id,
            ])?>
        </div>
        <div class="form-group">　
            <div class="col-sm-12 text-center">
                <div class="hr-line-dashed"></div>
                <span class="btn btn-primary" onclick="beforSubmit()">保存</span>
                <span class="btn btn-white" onclick="history.go(-1)">返回</span>
            </div>
        </div>
    </div>
</div>

<script>
    function beforSubmit() {
        var val =  $('input[name="ReplyNews[attachment_id]"]').val();
        if (!val){
            rfAffirm('请选择图文');
            return false;
        }

        $('#w0').submit();
    }
</script>
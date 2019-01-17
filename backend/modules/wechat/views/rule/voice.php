<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">回复内容</h4>
    </div>
    <div class="box-body">
        <div class="col-lg-12">
            <?= \backend\widgets\wechatselectattachment\Select::widget([
                'name' => 'ReplyVoice[media_id]',
                'value' => $moduleModel->media_id,
                'type' => 'voice',
                'label' => '语音',
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
        var val = $('input[name="ReplyVoice[media_id]"]').val();
        if (!val){
            rfAffirm('请选择语音');
            return false;
        }

        $('#w0').submit();
    }
</script>
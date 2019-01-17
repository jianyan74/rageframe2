<div class="box">
    <div class="box-header with-border">
        <h4 class="box-title">回复内容</h4>
    </div>
    <div class="box-body">
        <div class="col-sm-12">
            <?= $form->field($moduleModel, 'title')->textInput() ?>
            <?= \backend\widgets\wechatselectattachment\Select::widget([
                'name' => 'ReplyVideo[media_id]',
                'value' => $moduleModel->media_id,
                'type' => 'video',
                'label' => '视频',
            ])?>
            <?= $form->field($moduleModel, 'description')->textarea() ?>
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
        var val = $('input[name="ReplyVideo[media_id]"]').val();
        if (!val){
            rfAffirm('请选择视频');
            return false;
        }

        $('#w0').submit();
    }
</script>
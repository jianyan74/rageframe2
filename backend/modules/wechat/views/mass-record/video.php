<?= \backend\widgets\wechatselectattachment\Select::widget([
    'name' => 'SendForm[media_id]',
    'value' => $model->media_id,
    'type' => 'video',
    'label' => '视频',
])?>

<script>
    function beforSubmit() {
        var val =  $('input[name="SendForm[media_id]"]').val();
        if (!val){
            rfAffirm('请选择视频');
            return false;
        }

        $('#w0').submit();
    }
</script>
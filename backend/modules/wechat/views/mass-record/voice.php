<?= \backend\widgets\wechatselectattachment\Select::widget([
    'name' => 'SendForm[media_id]',
    'value' => $model->media_id,
    'type' => 'voice',
    'label' => '语音',
])?>

<script>
    function beforSubmit() {
        var val =  $('input[name="SendForm[media_id]"]').val();
        if (!val){
            rfAffirm('请选择语音');
            return false;
        }

        $('#w0').submit();
    }
</script>
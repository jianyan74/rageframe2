<?= \backend\widgets\wechatselectattachment\Select::widget([
    'name' => 'SendForm[media_id]',
    'value' => $model->media_id,
    'type' => 'image',
    'label' => '图片',
])?>

<script>
    function beforSubmit() {
        var val =  $('input[name="SendForm[media_id]"]').val();
        if (!val){
            rfAffirm('请选择照片');
            return false;
        }

        $('#w0').submit();
    }
</script>

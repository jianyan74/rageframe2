<?= \backend\widgets\wechatselectattachment\Select::widget([
    'name' => 'SendForm[attachment_id]',
    'value' => $model->attachment_id,
    'type' => 'news',
    'label' => '图文',
])?>

<script>
    function beforSubmit() {
        var val =  $('input[name="SendForm[attachment_id]"]').val();
        if (!val){
            rfAffirm('请选择图文');
            return false;
        }

        $('#w0').submit();
    }
</script>
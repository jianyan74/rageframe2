<?= $form->field($model, 'content')->textarea() ?>

<script>
    function beforSubmit() {
        var val =  $('#sendform-content').val();
        if (!val){
            rfAffirm('请填写内容');
            return false;
        }

        $('#w0').submit();
    }
</script>

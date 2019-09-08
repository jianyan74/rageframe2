<?php
use common\helpers\Html;
?>
<style>
    .colpick {
        z-index: 99 ;
    }
    .input-group {
        width: 100%;
    }
    .input-group-addon i {
        display: inline-block;
        cursor: pointer;
        height: 16px;
        vertical-align: text-top;
        width: 16px;
    }
</style>
<div class="form-group field-curd-address">
    <div class="row">
        <div class="col-lg-4">
            <div class="input-group">
                <?= Html::textInput($name, $value, [
                    'class' => 'form-control',
                    'placeholder' => '请选择颜色'
                ]); ?>
                <span id="<?= $boxId; ?>" class="input-group-addon" style="padding:2px;width:30px;background-color: #eee;">
                    <i style="background-color:<?= '#'.$value; ?>"></i>
                </span>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $(function() {
        $('#<?= $boxId; ?>').colpick({
            colorScheme: 'dark',
            layout: 'rgbhex',
            color: '<?= '#'.$value; ?>',
            onSubmit:function(hsb,hex,rgb,el) {
                $(el).children().css('background-color', '#'+hex);
                $(el).colpickHide();
                $(el).prev().val(hex);
            }
        }).keyup(function(){
        });
    });
</script>

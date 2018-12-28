<?php
use yii\helpers\Html;
use common\enums\StatusEnum;

?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']);?>
    <?php if($row['is_hide_remark'] != StatusEnum::ENABLED){ ?>
        (<?= $row['remark']?>)
    <?php } ?>
    <div class="col-sm-push-10">
        <?= Html::checkboxList('config[' . $row['name'] . ']', unserialize($row['value']), $option);?>
    </div>
</div>
<?php
use yii\helpers\Html;
use common\enums\StatusEnum;
?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']);?>
    <?php if($row['is_hide_remark'] != StatusEnum::ENABLED){ ?>
        (<?= $row['remark']?>)
    <?php } ?>
    <?= Html::input('text', 'config[' . $row['name'] . ']', $row['value'], ['class' => 'form-control']);?>
</div>
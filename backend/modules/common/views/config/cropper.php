<?php

use common\helpers\Html;
use common\enums\StatusEnum;

?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']); ?>
    <?php if ($row['is_hide_remark'] != StatusEnum::ENABLED) { ?>
        (<?= $row['remark'] ?>)
    <?php } ?>
    <div class="col-sm-push-10">
        <?= \backend\widgets\cropper\Cropper::widget([
            'name' => "config[" . $row['name'] . "]",
            'value' => $row['value']['data'] ?? $row['default_value'],
            'theme' => 'default',
        ]) ?>
    </div>
</div>
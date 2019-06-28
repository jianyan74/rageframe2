<?php

use common\helpers\Html;
use common\enums\StatusEnum;

$value = isset($row['value']['data']) ? unserialize($row['value']['data']) : $row['default_value'];
?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']); ?>
    <?php if ($row['is_hide_remark'] != StatusEnum::ENABLED) { ?>
        (<?= $row['remark'] ?>)
    <?php } ?>
    <div class="col-sm-push-10">
        <?= Html::checkboxList('config[' . $row['name'] . ']', $value, $option); ?>
    </div>
</div>
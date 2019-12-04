<?php

use common\helpers\Html;
use common\enums\StatusEnum;

?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']); ?>
    <?php if ($row['is_hide_remark'] != StatusEnum::ENABLED) { ?>
        <small><?= \yii\helpers\HtmlPurifier::process($row['remark']) ?></small>
    <?php } ?>
    <div class="input-group">
        <?= Html::input('text', 'config[' . $row['name'] . ']', $row['value']['data'] ?? $row['default_value'],
            ['class' => 'form-control', 'id' => $row['id']]); ?>
        <span class="input-group-btn">
            <span class="btn btn-white" onclick="createKey(<?= $row['extra'] ?>, <?= $row['id'] ?>)">生成新的</span>
        </span>
    </div>
</div>
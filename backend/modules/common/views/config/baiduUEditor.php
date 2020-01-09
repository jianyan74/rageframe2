<?php

use common\helpers\Html;
use common\enums\StatusEnum;

?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']); ?>
    <?php if ($row['is_hide_remark'] != StatusEnum::ENABLED) { ?>
        <small><?= \yii\helpers\HtmlPurifier::process($row['remark']) ?></small>
    <?php } ?>
    <?= \common\widgets\ueditor\UEditor::widget([
        'id' => "config[" . $row['name'] . "]",
        'attribute' => $row['name'],
        'name' => "config[" . $row['name'] . "]",
        'value' => $row['value']['data'] ?? $row['default_value'],
    ]) ?>
</div>
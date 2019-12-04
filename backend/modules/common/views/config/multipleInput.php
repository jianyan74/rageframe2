<?php

use yii\helpers\Json;
use common\helpers\Html;
use common\enums\StatusEnum;

$value = isset($row['value']['data']) ? Json::decode($row['value']['data']) : [];

$columns = [];
foreach ($option as $key => $v) {
    $columns[] = [
        'name' => $key,
        'title' => $v,
        'enableError' => false,
        'options' => [
            'class' => 'input-priority'
        ]
    ];
}
?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']); ?>
    <?php if ($row['is_hide_remark'] != StatusEnum::ENABLED) { ?>
        <small><?= \yii\helpers\HtmlPurifier::process($row['remark']) ?></small>
    <?php } ?>
    <div class="col-sm-push-10">
        <?= unclead\multipleinput\MultipleInput::widget([
            'max' => 20,
            'name' => "config[" . $row['name'] . "]",
            'value' => $value,
            'columns' => $columns
        ]) ?>
    </div>
</div>
<?php

use yii\helpers\Html;
use common\enums\StatusEnum;
?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']);?>
    <?php if($row['is_hide_remark'] != StatusEnum::ENABLED){ ?>
        (<?= $row['remark']?>)
    <?php } ?>
    <?= \common\widgets\ueditor\UEditor::widget([
        'id' => "config[".$row['name']."]",
        'attribute' => $row['name'],
        'name' => $row['name'],
        'value' => $row['value'],
    ]) ?>
</div>
<?php

use common\helpers\Html;
use common\enums\StatusEnum;

$value = $row['value']['data'] ?? $row['default_value'];
?>

<div class="form-group">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']); ?>
    <?php if ($row['is_hide_remark'] != StatusEnum::ENABLED) { ?>
        <small><?= \yii\helpers\HtmlPurifier::process($row['remark']) ?></small>
    <?php } ?>
    <div class="col-sm-push-10">
        <?php foreach ($option as $key => $v) { ?>
            <label class="radio-inline">
                <input type="radio" name="config[<?= $row['name'] ?>]" class="radio" value="<?= $key ?>"
                       <?php if ($key == $value){ ?>checked<?php } ?>><?= $v ?>
            </label>
        <?php } ?>
    </div>
</div>
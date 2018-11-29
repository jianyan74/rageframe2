<?php

use yii\helpers\Html;
use common\enums\StatusEnum;
?>

<div class="form-group" style="padding-left: -15px">
    <?= Html::label($row['title'], $row['name'], ['class' => 'control-label demo']);?>
    <?php if ($row['is_hide_remark'] != StatusEnum::ENABLED){ ?>
        (<?= $row['remark']?>)
    <?php } ?>
    <div class="col-sm-push-10">
        <?= \common\widgets\webuploader\Images::widget([
            'name' => "config[".$row['name']."]",
            'value' => unserialize($row['value']),
            'config' => [
                'pick' => [
                    'multiple' => true,
                ],
            ]
        ])?>
    </div>
</div>
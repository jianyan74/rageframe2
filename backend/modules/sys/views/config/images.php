<?php

use yii\helpers\Html;
use common\enums\StatusEnum;
?>

<div class="form-group" style="padding-left: -15px">
    <?php echo Html::label($row['title'], $row['name'], ['class' => 'control-label demo']);?>
    <?php if($row['is_hide_remark'] != StatusEnum::ENABLED){ ?>
        (<?php echo $row['remark']?>)
    <?php } ?>
    <div class="col-sm-push-10">
        <?php echo \backend\widgets\webuploader\Image::widget([
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
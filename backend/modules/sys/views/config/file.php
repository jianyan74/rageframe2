<?php
use yii\helpers\Html;
use common\enums\StatusEnum;
?>

<div class="form-group">
    <?php echo Html::label($row['title'], $row['name'], ['class' => 'control-label demo']);?>
    <?php if($row['is_hide_remark'] != StatusEnum::ENABLED){ ?>
        (<?php echo $row['remark']?>)
    <?php } ?>
    <div class="col-sm-push-10" style="padding-left: 15px">
        <?php echo \common\widgets\webuploader\Files::widget([
            'name'  =>"config[".$row['name']."]",
            'value' => $row['value'],
            'config' => [
                'pick' => [
                    'multiple' => false,
                ],
            ]
        ])?>
    </div>
</div>
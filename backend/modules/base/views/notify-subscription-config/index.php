<?php

use yii\widgets\ActiveForm;
use common\helpers\Html;

$this->title = '提醒管理';
$this->params['breadcrumbs'][] = $this->title;

?>

<?= Html::cssFile('@web/resources/css/checkbox.css'); ?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <?php $form = ActiveForm::begin(); ?>
            <div class="box-body">
                <table class="table">
                    <thead>
                    <tr>
                        <th class="col-md-2"></th>
                        <?php foreach ($typeExplain as $type) { ?>
                            <th class="col-md-2 text-center"><?= Html::encode($type) ?></th>
                        <?php } ?>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($valueExplain as $item => $value) { ?>
                        <tr class="text-center">
                            <td><?= Html::encode($value) ?></td>
                            <?php foreach ($typeExplain as $key => $type) { ?>
                                <td class="text-center">
                                    <div class="checkbox checkbox-primary">
                                        <?= Html::checkbox(
                                            Html::getInputName($model, $key) . '[' . $item . ']',
                                            (isset($model->$key[$item]) && !empty($model->$key[$item])) ? true : false,
                                            [
                                                'class' => "styled",
                                                'id' => $item . $key,
                                            ]) ?>
                                        <label for="<?= $item . $key; ?>"></label>
                                    </div>
                                </td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
            </div>
            <!-- /.box-body -->
            <div class="box-footer">
                <div class="col-sm-12 text-center">
                    <button class="btn btn-primary" type="submit">保存</button>
                </div>
            </div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
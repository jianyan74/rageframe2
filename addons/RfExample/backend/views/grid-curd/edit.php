<?php
use yii\widgets\ActiveForm;

$this->title = $model->isNewRecord ? '创建' : '编辑';
$this->params['breadcrumbs'][] = ['label' => 'Curd', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>

<div class="row">
    <div class="col-lg-12">
        <div class="box">
            <?php $form = ActiveForm::begin([]); ?>
            <?= $this->render('../curd/content', [
                'model' => $model,
                'form' => $form,
            ]); ?>
            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>

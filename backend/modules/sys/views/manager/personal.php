<?php
use common\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '个人中心';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?= $this->render('_form', [
        'model' => $model,
        'backBtn' => '',
]) ?>

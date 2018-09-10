<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = '个人中心';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<?php echo $this->render('_form', [
        'model' => $model,
        'backBtn' => '',
]) ?>

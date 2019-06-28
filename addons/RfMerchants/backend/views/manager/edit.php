<?php
$this->title = '编辑';
$this->params['breadcrumbs'][] = ['label' => '商户管理', 'url' => ['merchant/index']];
$this->params['breadcrumbs'][] = ['label' => '用户管理', 'url' => ['index', 'merchant_id' => $merchant_id]];
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<?= $this->render('_form', [
    'model' => $model,
    'backBtn' => '<span class="btn btn-white" onclick="history.go(-1)">返回</span>',
]) ?>

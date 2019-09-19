## 代码模板

目录

- 首页
- 编辑/创建页
- Ajax 模态框

### 首页

示例一

```
<?php

use yii\grid\GridView;
use common\helpers\Html;

$this->title = '代码模板';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <div class="box-body table-responsive">
                <!-- 你的显示代码 -->
            </div>
        </div>
    </div>
</div>

 ```
 
 示例二
 
  ```
<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['test/index1']) ?>"> 测试1</a></li>
                <li><a href="<?= Url::to(['test/index2']) ?>"> 测试2</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
                    ]); ?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <!-- 你的显示代码 -->
                </div>
            </div>
        </div>
    </div>
</div>
  ```
 
 ### 编辑/创建页
 
 ```
 <?php
 
 use yii\widgets\ActiveForm;
 use common\enums\GenderEnum;
 use common\enums\StatusEnum;
 
 $this->title = '编辑';
 $this->params['breadcrumbs'][] = ['label' => '首页', 'url' => ['index']];
 $this->params['breadcrumbs'][] = ['label' => $this->title];
 ?>
 
<div class="row">
 <div class="col-lg-12">
     <div class="box">
         <div class="box-header with-border">
             <h3 class="box-title">基本信息</h3>
         </div>
         <?php $form = ActiveForm::begin([
             'fieldConfig' => [
                 'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}{hint}{error}</div>",
             ]
         ]); ?>
         <div class="box-body">
             <!-- 你的表单代码 -->
         </div>
         <!-- /.box-body -->
         <div class="box-footer">
             <div class="col-sm-12 text-center">
                 <button class="btn btn-primary" type="submit">保存</button>
                 <span class="btn btn-white" onclick="history.go(-1)">返回</span>
             </div>
         </div>
         <?php ActiveForm::end(); ?>
     </div>
 </div>
</div>
```

### Ajax 模态框

```
<?php

use yii\widgets\ActiveForm;
use common\helpers\Url;

$form = ActiveForm::begin([
    'id' => $model->formName(),
    'enableAjaxValidation' => true,
    'validationUrl' => Url::to(['ajax-edit', 'id' => $model['id']]),
    'fieldConfig' => [
        'template' => "<div class='col-sm-2 text-right'>{label}</div><div class='col-sm-10'>{input}\n{hint}\n{error}</div>",
    ]
]);
?>

<div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
    <h4 class="modal-title">基本信息</h4>
</div>
<div class="modal-body">
     <!-- 你的表单代码 -->
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-white" data-dismiss="modal">关闭</button>
    <button class="btn btn-primary" type="submit">保存</button>
</div>

<?php ActiveForm::end(); ?>
```
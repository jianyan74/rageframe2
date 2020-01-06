<?php
use common\helpers\Html;
use yii\grid\GridView;

$this->title = '商户管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        'id',
                        'title',
                        [
                            'label' => '账户金额',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "剩余：" . $model->user_money . '<br>' .
                                    "累计：" . $model->accumulate_money;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template'=> '{member} {role} {ajax-edit} {status} {destroy}',
                            'buttons' => [
                                'member' => function ($url, $model, $key) {
                                    return Html::linkButton(['member/index', 'merchant_id' => $model->id], '用户管理');
                                },
                                'role' => function ($url, $model, $key) {
                                    return Html::linkButton(['role/index', 'merchant_id' => $model->id], '角色管理');
                                },
                                'ajax-edit' => function ($url, $model, $key) {
                                    return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
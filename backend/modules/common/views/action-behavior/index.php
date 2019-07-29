<?php
use yii\grid\GridView;
use common\helpers\Html;
use common\enums\WhetherEnum;

$this->title = '行为监控';
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
                    ])?>
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
                        ],
                        'url',
                        [
                            'attribute' => 'method',
                            'value' => function ($model, $key, $index, $column) use ($methodExplain) {
                                return $methodExplain[$model->method];
                            },
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'method', $methodExplain, [
                                'prompt' => '全部',
                                'class' => 'form-control'
                            ])
                        ],
                        'remark',
                        [
                            'attribute' => 'action',
                            'value' => function ($model, $key, $index, $column) use ($actionExplain) {
                                return $actionExplain[$model->action];
                            },
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'action', $actionExplain, [
                                'prompt' => '全部',
                                'class' => 'form-control'
                            ])
                        ],
                        [
                            'attribute' => 'is_ajax',
                            'value' => function ($model, $key, $index, $column) use ($ajaxExplain) {
                                return $ajaxExplain[$model->is_ajax];
                            },
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'is_ajax', $ajaxExplain, [
                                'prompt' => '全部',
                                'class' => 'form-control'
                            ])
                        ],
                        [
                            'attribute' => 'is_record_post',
                            'value' => function ($model, $key, $index, $column) {
                                return Html::whether($model->is_record_post);
                            },
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'is_record_post', WhetherEnum::$listExplain, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            )
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template'=> '{ajax-edit} {status} {delete}',
                            'buttons' => [
                                'ajax-edit' => function ($url, $model, $key) {
                                    return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete','id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
        </div>
    </div>
</div>
<?php

use common\enums\AppEnum;
use yii\grid\GridView;
use common\helpers\Html;
use common\enums\WhetherEnum;
use common\enums\MethodEnum;
use common\enums\MessageLevelEnum;

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
                    ]) ?>
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
                        [
                            'attribute' => 'app_id',
                            'filter' => Html::activeDropDownList($searchModel, 'app_id', AppEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control'
                            ]),
                            'value' => function ($model) {
                                return AppEnum::getValue($model->app_id);
                            },
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        'url',
                        [
                            'attribute' => 'method',
                            'value' => function ($model, $key, $index, $column) {
                                return Html::method($model->method);
                            },
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'method', MethodEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control'
                            ])
                        ],
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
                            'filter' => Html::activeDropDownList($searchModel, 'is_record_post',
                                WhetherEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            )
                        ],
                        [
                            'attribute' => 'level',
                            'value' => function ($model, $key, $index, $column) {
                                return Html::messageLevel($model->level);
                            },
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'level',
                                MessageLevelEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            )
                        ],
                        'remark',
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{ajax-edit} {status} {delete}',
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
                                    return Html::delete(['delete', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
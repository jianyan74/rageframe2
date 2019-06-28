<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\grid\GridView;

$this->title = '配置管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['config/index']) ?>"> 配置管理</a></li>
                <li><a href="<?= Url::to(['config-cate/index']) ?>"> 配置分类</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= GridView::widget([
                        'dataProvider' => $dataProvider,
                        'filterModel' => $searchModel,
                        //重新定义分页样式
                        'tableOptions' => ['class' => 'table table-hover'],
                        'columns' => [
                            [
                                'class' => 'yii\grid\SerialColumn',
                            ],
                            'title',
                            'name',
                            [
                                'attribute' => 'sort',
                                'value' => function ($model) {
                                    return Html::sort($model->sort);
                                },
                                'filter' => false,
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'label' => '类别',
                                'attribute' => 'cate.title',
                                'filter' => Html::activeDropDownList($searchModel, 'cate_id', $cateDropDownList, [
                                        'prompt' => '全部',
                                        'class' => 'form-control'
                                    ]
                                ),
                            ],
                            [
                                'label' => '属性',
                                'attribute' => 'type',
                                'value' => function ($model, $key, $index, $column) {
                                    return Yii::$app->params['configTypeList'][$model->type];
                                },
                                'filter' => Html::activeDropDownList($searchModel, 'type',
                                    Yii::$app->params['configTypeList'], [
                                        'prompt' => '全部',
                                        'class' => 'form-control'
                                    ]
                                ),
                                'headerOptions' => ['class' => 'col-md-1'],
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{edit} {status} {destroy}',
                                'buttons' => [
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    },
                                    'status' => function ($url, $model, $key) {
                                        return Html::status($model->status);
                                    },
                                    'destroy' => function ($url, $model, $key) {
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
</div>
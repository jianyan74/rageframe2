<?php

use common\helpers\Url;
use common\helpers\Html;
use jianyan\treegrid\TreeGrid;

$this->title = '配置分类';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li><a href="<?= Url::to(['config/index']) ?>"> 配置管理</a></li>
                <li class="active"><a href="<?= Url::to(['config-cate/index']) ?>"> 分类管理</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
                </li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
                    <?= TreeGrid::widget([
                        'dataProvider' => $dataProvider,
                        'keyColumnName' => 'id',
                        'parentColumnName' => 'pid',
                        'parentRootValue' => '0', //first parentId value
                        'pluginOptions' => [
                            'initialState' => 'collapsed',
                        ],
                        'options' => ['class' => 'table table-hover'],
                        'columns' => [
                            [
                                'attribute' => 'title',
                                'format' => 'raw',
                                'value' => function ($model, $key, $index, $column) {
                                    $str = Html::tag('span', $model->title, [
                                        'class' => 'm-l-sm'
                                    ]);
                                    $str .= Html::a(' <i class="icon ion-android-add-circle"></i>',
                                        ['ajax-edit', 'pid' => $model['id']], [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    return $str;
                                }
                            ],
                            [
                                'attribute' => 'sort',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model, $key, $index, $column) {
                                    return Html::sort($model->sort);
                                }
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template' => '{edit} {status} {delete}',
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
                                    'delete' => function ($url, $model, $key) {
                                        return Html::delete(['delete', 'id' => $model->id]);
                                    },
                                ],
                            ],
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
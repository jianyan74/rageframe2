<?php

use common\helpers\Url;
use common\helpers\Html;
use jianyan\treegrid\TreeGrid;

$this->title = '菜单管理';
$this->params['breadcrumbs'][] = ['label' =>  $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php foreach ($cates as $cate){ ?>
                    <li class="<?php if ($cate['id'] == $cate_id ){ echo 'active' ;}?>"><a href="<?= Url::to(['index', 'cate_id' => $cate['id']]) ?>"> <?= $cate['title'] ?></a></li>
                <?php } ?>
                <li><a href="<?= Url::to(['menu-cate/index']) ?>"> 菜单分类</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit', 'cate_id' => $cate_id], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalLg',
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
                                'value' => function ($model, $key, $index, $column){
                                    $str = Html::tag('span', $model->title, [
                                        'class' => 'm-l-sm'
                                    ]);
                                    $str .= Html::a(' <i class="icon ion-android-add-circle"></i>', ['ajax-edit', 'pid' => $model['id']], [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                    return $str;
                                }
                            ],
                            'url',
                            [
                                'attribute' => 'icon',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model, $key, $index, $column){
                                    return Html::tag('span', '', [
                                        'class' => 'fa ' . $model['icon']
                                    ]);
                                }
                            ],
                            [
                                'attribute' => 'dev',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model, $key, $index, $column){
                                    return Html::whether($model['dev']);
                                }
                            ],
                            [
                                'attribute' => 'sort',
                                'format' => 'raw',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model, $key, $index, $column){
                                    return  Html::sort($model->sort);
                                }
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template'=> '{edit} {status} {delete}',
                                'buttons' => [
                                    'edit' => function ($url, $model, $key) {
                                        return Html::edit(['ajax-edit','id' => $model->id], '编辑', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
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
                        ]
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
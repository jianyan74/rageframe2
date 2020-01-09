<?php

use common\helpers\Url;
use common\helpers\Html;
use yii\grid\GridView;

$this->title = '菜单管理';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <?php foreach ($cates as $cate) { ?>
                    <li><a href="<?= Url::to(['menu/index', 'cate_id' => $cate['id']]) ?>"> <?= $cate['title'] ?></a></li>
                <?php } ?>
                <li class="active"><a href="<?= Url::to(['menu-cate/index']) ?>"> 菜单分类</a></li>
                <li class="pull-right">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]); ?>
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
                            'id',
                            [
                                'attribute' => 'title',
                                'value' => function ($model) {
                                    $str = !empty($model->addons_name) ? ' <span class="label label-warning">插件</span>' : '';

                                    return $model->title . $str;
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'icon',
                                'value' => function ($model) {
                                    return '<i class="fa ' . $model->icon . '">';
                                },
                                'filter' => false,
                                'format' => 'raw',
                            ],
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
                                'attribute' => 'is_default_show',
                                'value' => function ($model) {
                                    return Html::whether($model->is_default_show);
                                },
                                'filter' => false,
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'addon_centre',
                                'value' => function ($model) {
                                    return Html::whether($model->addon_centre);
                                },
                                'filter' => false,
                                'format' => 'raw',
                            ],
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
                                        if ($model->is_addon == \common\enums\WhetherEnum::DISABLED) {
                                            return Html::delete(['delete', 'id' => $model->id]);
                                        }

                                        return false;
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
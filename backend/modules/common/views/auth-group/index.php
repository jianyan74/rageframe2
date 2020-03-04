<?php
use common\enums\WhetherEnum;
use common\helpers\Html;
use yii\grid\GridView;

$this->title = '权限分组';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit']); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,

                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        'title',
                        [
                            'attribute' => 'is_free',
                            'value' => function ($model){
                                return WhetherEnum::getValue($model['is_free']);
                            }
                        ],
                        [
                            'attribute' => 'price'
                        ],
                        [
                            'attribute' => 'count_unit',
                        ],
                        [
                            'attribute' => 'sort',
                            'value' => function ($model) {
                                return Html::sort($model['sort']);
                            },
                            'filter' => false,
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],

                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {destroy}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model['id']], '编辑', [
                                    ]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model['status']);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['delete', 'id' => $model['id']]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

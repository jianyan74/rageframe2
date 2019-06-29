<?php
use common\helpers\Url;
use common\helpers\Html;
use leandrogehlen\treegrid\TreeGrid;

$this->title = '角色管理';
$this->params['breadcrumbs'][] = ['label' => '商户管理', 'url' => ['merchant/index']];
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit', 'merchant_id' => $merchant_id]); ?>
                </div>
            </div>
            <div class="box-body table-responsive">
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
                            'label' => '角色名称',
                            'format' => 'raw',
                            'value' => function ($model, $key, $index, $column) use ($merchant_id) {
                                $str = Html::tag('span', $model['title'], [
                                    'class' => 'm-l-sm'
                                ]);
                                $str .= Html::a(' <i class="icon ion-android-add-circle"></i>', ['edit', 'pid' => $model['id'], 'merchant_id' => $merchant_id]);
                                return $str;
                            }
                        ],
                        [
                            'attribute' => 'sort',
                            'label' => '排序',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model, $key, $index, $column){
                                return  Html::sort($model['sort']);
                            }
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template'=> '{edit} {status} {delete}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) use ($merchant_id) {
                                    return Html::edit(['edit', 'id' => $model['id'], 'merchant_id' => $merchant_id]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model['status']);
                                },
                                'delete' => function ($url, $model, $key) use ($merchant_id) {
                                    return Html::delete(['delete', 'id' => $model['id'], 'merchant_id' => $merchant_id]);
                                },
                            ],
                        ],
                    ]
                ]); ?>
            </div>
        </div>
    </div>
</div>
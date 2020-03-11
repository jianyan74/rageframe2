<?php

use yii\grid\GridView;
use common\helpers\Html;
use addons\TinyDistribution\common\enums\StateEnum;

$this->title = '商户提现';
$this->params['breadcrumbs'][] = $this->title;

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover rf-table'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'label' => '商户',
                            'filter' => false, //不显示搜索框
                            'format' => 'raw',
                            'value' => function ($model) {
                                return $model->merchant->title . '<br>' . $model->merchant->mobile;
                            }
                        ],
                        'withdraw_no',
                        'bank_name',
                        'account_number',
                        [
                            'attribute' => 'realname',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'mobile',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'cash',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'state',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'state', StateEnum::getMap(), [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'value' => function ($model) {
                                return StateEnum::html($model->state);
                            },
                        ],
                        [
                            'label' => '申请时间',
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{pass} {refuse}',
                            'buttons' => [
                                'pass' => function ($url, $model, $key) {
                                    if ($model->state == StateEnum::DISABLED) {
                                        return Html::a('通过', ['pass', 'id' => $model->id], [
                                                'class' => 'blue',
                                                'data-toggle' => 'modal',
                                                'data-target' => '#ajaxModal',
                                            ]) . ' | ';
                                    }
                                },
                                'refuse' => function ($url, $model, $key) {
                                    if ($model->state == StateEnum::DISABLED) {
                                        return Html::a('拒绝', ['refuse', 'id' => $model->id], [
                                            'class' => 'red',
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                        ]);
                                    }
                                },
                            ],
                        ],
                    ],
                ]); ?>
                <!-- /.box-body -->
            </div>
            <!-- /.box -->
        </div>
    </div>
</div>
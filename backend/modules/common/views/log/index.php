<?php

use yii\grid\GridView;
use common\enums\AppEnum;
use common\helpers\Html;
use common\helpers\DebrisHelper;

$this->title = '全局日志';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::linkButton(['flow-stat'], '<i class="fa fa-bar-chart"></i> 流量报表统计', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalMax',
                    ]) ?>
                    <?= Html::linkButton(['stat'], '<i class="fa fa-area-chart"></i> 异常请求报表统计', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalMax',
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
                        'id',
                        [
                            'attribute' => 'method',
                            'headerOptions' => ['class' => 'col-md-1'],
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
                        [
                            'label' => '用户',
                            'value' => function ($model) {
                                return Yii::$app->services->backend->getUserName($model);
                            },
                            'filter' => false, //不显示搜索框
                            'format' => 'raw',
                        ],
                        'url',
                        [
                            'label' => '位置信息',
                            'value' => function ($model) {
                                $str = [];
                                $str[] = DebrisHelper::analysisIp($model->ip);
                                $str[] = DebrisHelper::long2ip($model->ip);
                                return implode("</br>", $str);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'error_code',
                            'label' => '状态码',
                            'value' => function ($model) {
                                if ($model->error_code < 300) {
                                    return '<span class="label label-primary">' . $model->error_code . '</span>';
                                } else {
                                    return '<span class="label label-danger">' . $model->error_code . '</span>';
                                }
                            },
                            'headerOptions' => ['class' => 'col-md-1'],
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['view', 'id' => $model->id], '查看详情', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModalLg',
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>

            </div>
        </div>
    </div>
</div>

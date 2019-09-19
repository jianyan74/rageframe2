<?php

use yii\grid\GridView;
use common\enums\AppEnum;
use common\helpers\Html;
use common\helpers\Url;
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
                    <?= Html::linkButton(['stat'], '<i class="fa fa-area-chart"></i> 异常请求报表统计', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModalMax',
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
                        'id',
                        [
                            'attribute' => 'method',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'attribute' => 'app_id',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'label' => '用户',
                            'value' => function ($model) {
                                if (empty($model->user_id)) {
                                    return '游客';
                                } elseif (AppEnum::BACKEND == $model->app_id){
                                    return $model->manager->username;
                                } elseif (in_array($model->app_id, [AppEnum::API, AppEnum::FRONTEND, AppEnum::WECHAT])){
                                    return $model->member->username;
                                }
                            },
                            'filter' => false, //不显示搜索框
                        ],
                        'url',
                        [
                            'label' => 'ip',
                            'attribute' => 'ip',
                            'value' => function ($model) {
                                return DebrisHelper::long2ip($model->ip);
                            },
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'label' => '地区',
                            'value' => function ($model) {
                                return DebrisHelper::analysisIp($model->ip);
                            },
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

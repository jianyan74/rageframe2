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
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['index']) ?>"> <?= $this->title; ?></a></li>
                <li><a href="<?= Url::to(['ip-statistics']) ?>"> IP统计</a></li>
                <li><a href="<?= Url::to(['statistics']) ?>"> 数据统计</a></li>
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
                                'attribute' => 'user_id',
                                'filter' => Html::activeTextInput($searchModel, 'user_id', [
                                        'class' => 'form-control',
                                        'placeholder' => '用户ID'
                                    ]
                                ),
                                'value' => function ($model) {
                                    return Yii::$app->services->backend->getUserName($model);
                                },
                                'format' => 'raw',
                            ],
                            'url',
                            [
                                'label' => '位置信息',
                                'attribute' => 'ip',
                                'value' => function ($model) {
                                    $str = [];
                                    $str[] = DebrisHelper::analysisIp($model->ip);
                                    $str[] = DebrisHelper::long2ip($model->ip);
                                    $str[] = $model->ip;
                                    return implode("</br>", $str);
                                },
                                'format' => 'raw',
                            ],
                            [
                                'attribute' => 'error_code',
                                'label' => '状态码',
                                'headerOptions' => ['class' => 'col-md-1'],
                                'value' => function ($model) {
                                    if ($model->error_code < 300) {
                                        return '<span class="label label-primary">' . $model->error_code . '</span>';
                                    } else {
                                        return '<span class="label label-danger">' . $model->error_code . '</span>';
                                    }
                                },
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
</div>



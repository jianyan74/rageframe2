<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\enums\AppEnum;
use common\helpers\DebrisHelper;

$this->title = '行为日志';
$this->params['breadcrumbs'][] = ['label' => $this->title, 'url' => ['index']];
?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
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
                        'behavior',
                        'url',
                        [
                            'label' => '位置信息',
                            'value' => function ($model) {
                                $str = [];
                                $str[] = DebrisHelper::analysisIp($model->ip);
                                $str[] = DebrisHelper::long2ip($model->ip);
                                return implode('</br>', $str);
                            },
                            'format' => 'raw',
                        ],
                        'remark',
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

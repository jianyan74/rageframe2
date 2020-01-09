<?php

use yii\grid\GridView;
use common\helpers\Html;

$this->title = '公告列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-2">
        <div class="box box-solid p-xs rfAddonMenu">
            <div class="box-header with-border">
                <h3 class="rf-box-title">消息提醒</h3>
            </div>
            <div class="box-body no-padding">
                <?= $this->render('_nav') ?>
            </div>
        </div>
    </div>
    <div class="col-sm-10">
        <div class="box">
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        'id',
                        'notifySenderForMember.title',
                        [
                            'label' => '来自',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return $model->notifySenderForMember->senderForManager->username ?? '';
                            }
                        ],
                        [
                            'label' => '浏览状态',
                            'filter' => false, //不显示搜索框
                            'format' => 'raw',
                            'value' => function ($model) {
                                $label = $model->is_read == 0 ? 'label-success' : 'label-default';
                                return Html::label($model->is_read == 1 ? '已读' : '未读', '', [
                                    'class' => "label " . $label
                                ]);
                            },
                        ],
                        [
                            'label' => '创建时间',
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{view}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['announce-view', 'id' => $model->id], '详情');
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
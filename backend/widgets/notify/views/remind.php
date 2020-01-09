<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\enums\SubscriptionReasonEnum;

$this->title = '提醒列表';
$this->params['breadcrumbs'][] = $this->title;
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
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::a('全部设为已读', ['read-all'], [
                        'onclick' => "rfTwiceAffirm(this, '确认全部设为已读么？', '可能会漏看一些关键信息，请谨慎操作');return false;"
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
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        [
                            'label' => '对应ID',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return '#' . $model->notify->target_id;
                            },
                        ],
                        'notify.content',
                        [
                            'label' => '创建时间',
                            'attribute' => 'created_at',
                            'headerOptions' => ['class' => 'col-md-2'],
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'label' => '查看时间',
                            'attribute' => 'updated_at',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                if ($model['updated_at'] == $model['created_at']) {
                                    return '现在';
                                }

                                return Yii::$app->formatter->asRelativeTime($model['updated_at']);
                            },
                        ],
                        [
                            'label' => '操作',
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model) {
                                switch ($model->notify->target_type) {
                                    case SubscriptionReasonEnum::LOG_CREATE :
                                        return Html::a('查看', ['/common/log/index'], [
                                            'class' => 'openContab blue',
                                            'data-title' => '全局日志',
                                        ]);
                                        break;
                                    case SubscriptionReasonEnum::BEHAVIOR_CREATE :
                                        return Html::a('查看', ['/common/action-log/index'], [
                                            'class' => 'openContab blue',
                                            'data-title' => '行为日志',
                                        ]);
                                        break;
                                    case SubscriptionReasonEnum::SMS_CREATE :
                                        return Html::a('查看', ['/common/sms-log/index'], [
                                            'class' => 'openContab blue',
                                            'data-title' => '短信日志',
                                        ]);
                                        break;
                                }
                            }
                        ]
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
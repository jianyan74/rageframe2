<?php
use yii\grid\GridView;
use common\helpers\Url;
use common\helpers\Html;

$this->title = '公告列表';
$this->params['breadcrumbs'][] = ['label' => $this->title];
?>

<div class="row">
    <div class="col-sm-12">
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active"><a href="<?= Url::to(['announce']) ?>"> 公告列表</a></li>
                <li><a href="<?= Url::to(['message'])?>"> 私信列表</a></li>
            </ul>
            <div class="tab-content">
                <div class="active tab-pane">
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
                            'notifySenderForManager.title',
                            [
                                'label'=> '来自',
                                'filter' => false, //不显示搜索框
                                'value' => function($model){
                                    return $model->notifySenderForManager->senderForManager->username ?? '';
                                }
                            ],
                            [
                                'label'=> '浏览状态',
                                'filter' => false, //不显示搜索框
                                'format' => 'raw',
                                'value' => function($model){
                                    $label = $model->is_read == 0 ? 'label-success' : 'label-default';
                                    return Html::label($model->is_read == 1 ? '已读' : '未读', '', [
                                        'class' => "label " . $label
                                    ]);
                                },
                            ],
                            [
                                'label'=> '创建时间',
                                'attribute' => 'created_at',
                                'filter' => false, //不显示搜索框
                                'format' => ['date', 'php:Y-m-d H:i'],
                            ],
                            [
                                'header' => "操作",
                                'class' => 'yii\grid\ActionColumn',
                                'template'=> '{view}',
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
</div>
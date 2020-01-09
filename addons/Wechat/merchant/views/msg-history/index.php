<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;

$this->title = '历史消息';
$this->params['breadcrumbs'][] = ['label' => $this->title];
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
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'label' => '昵称',
                            'attribute' => 'fans.nickname',
                            'filter' => false, //不显示搜索框
                        ],
                        [
                            'attribute' => 'type',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'label' => '内容',
                            'attribute' => 'message',
                            'value' => function ($model) {
                                $data = Yii::$app->wechatService->msgHistory->readMessage($model->type, $model->message);
                                return '<div style="max-width:515px; overflow:hidden; word-break:break-all; word-wrap:break-word;" class="emoji">' . $data . '</div>';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '触发规则',
                            'value' => function ($model) {
                                if (!$model->rule_id) {
                                    return '<span class="label label-default">未触发</span>';
                                } else {
                                    return '<span class="label label-info">' . $model->rule->name ?? '规则被删除' . '</span>';
                                }
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '触发回复',
                            'value' => function ($model) use ($moduleExplain) {
                                if (!$model->module) {
                                    return '<span class="label label-default">未触发</span>';
                                } else {
                                    $title = $moduleExplain[$model->module] ?? $model->module;
                                    return '<span class="label label-info">' . $title . '</span>';
                                }
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'module', $moduleExplain, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'format' => 'raw',
                        ],
                        'openid',
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{send-message} {delete}',
                            'buttons' => [
                                'send-message' => function ($url, $model, $key) {
                                    return Html::linkButton(['fans/send-message', 'openid' => $model->openid],
                                        '发送消息', [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModalLg',
                                        ]);
                                },
                                'delete' => function ($url, $model, $key) {
                                    return Html::delete(['delete', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<?php Yii::$app->view->registerJs(<<<js
  $('.emoji').each(function (i, data) {
        var text = $(data).html();
        var html = qqWechatEmotionParser(text);
        $(data).html(html)
    })
js
)?>

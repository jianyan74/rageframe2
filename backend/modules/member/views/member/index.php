<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;

$this->title = '会员信息';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['ajax-edit'], '创建', [
                        'data-toggle' => 'modal',
                        'data-target' => '#ajaxModal',
                    ]) ?>
                </div>
            </div>
            <div class="box-body table-responsive">
                <?= GridView::widget([
                    'dataProvider' => $dataProvider,
                    'filterModel' => $searchModel,
                    //重新定义分页样式
                    'tableOptions' => [
                        'class' => 'table table-hover rf-table',
                        'fixedNumber' => 2,
                        'fixedRightNumber' => 1,
                    ],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        [
                            'attribute' => 'id',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        [
                            'attribute' => 'head_portrait',
                            'value' => function ($model) {
                                return Html::img(ImageHelper::defaultHeaderPortrait(Html::encode($model->head_portrait)),
                                    [
                                        'class' => 'img-circle rf-img-md img-bordered-sm',
                                    ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        'username',
                        'realname',
                        [
                            'attribute' => 'mobile',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        'nickname',
                        [
                            'attribute' => 'memberLevel.name',
                            'value' => function ($model) {
                                return Html::tag('span', $model->memberLevel->name ?? '', [
                                    'class' => 'label label-primary'
                                ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        [
                            'label' => '账户金额',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "剩余：" . $model->account->user_money . '<br>' .
                                    "累计：" . $model->account->accumulate_money . '<br>' .
                                    "累计消费：" . abs($model->account->consume_money);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '账户积分',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "剩余：" . $model->account->user_integral . '<br>' .
                                    "累计：" . $model->account->accumulate_integral . '<br>' .
                                    "累计消费：" . abs($model->account->consume_integral);
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '最后登录',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "最后访问IP：" . $model->last_ip . '<br>' .
                                    "最后访问：" . Yii::$app->formatter->asDatetime($model->last_time) . '<br>' .
                                    "登录次数：" . $model->visit_count . '<br>' .
                                    "注册时间：" . Yii::$app->formatter->asDatetime($model->created_at) . '<br>';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'contentOptions' => ['class' => 'text-align-center'],
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{ajax-edit} {address} {update-level} {recharge} {edit} {status} {destroy}',
                            'buttons' => [
                                'ajax-edit' => function ($url, $model, $key) {
                                    return Html::a('账号密码', ['ajax-edit', 'id' => $model->id], [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                            'class' => 'blue'
                                        ]) . '<br>';
                                },
                                'address' => function ($url, $model, $key) {
                                    return Html::a('收货地址', ['address/index', 'member_id' => $model->id], [
                                            'class' => 'cyan'
                                        ]) . '<br>';
                                },
                                'update-level' => function ($url, $model, $key) {
                                    return Html::a('修改等级', ['update-level', 'id' => $model->id], [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                        'class' => 'green'
                                    ]) . '<br>';
                                },
                                'recharge' => function ($url, $model, $key) {
                                    return Html::a('充值', ['recharge', 'id' => $model->id], [
                                            'data-toggle' => 'modal',
                                            'data-target' => '#ajaxModal',
                                            'class' => 'orange'
                                        ]) . '<br>';
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::a('编辑', ['edit', 'id' => $model->id], [
                                            'class' => 'purple'
                                        ]) . '<br>';
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::a('删除', ['destroy', 'id' => $model->id], [
                                            'class' => 'red',
                                        ]) . '<br>';
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>
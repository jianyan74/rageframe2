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
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        [
                            'attribute' => 'head_portrait',
                            'value' => function ($model) {
                                return Html::img(ImageHelper::defaultHeaderPortrait(Html::encode($model->head_portrait)), [
                                        'class' => 'img-circle rf-img-md img-bordered-sm',
                                ]);
                            },
                            'filter' => false,
                            'format' => 'raw',
                        ],
                        [
                            'attribute' => 'username',
                            'filter' => false, //不显示搜索框
                        ],
                        'realname',
                        'mobile',
                        [
                            'label'=> '账户金额',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "余额：" . $model->user_money . '<br>'.
                                    "累积金额：" . $model->accumulate_money . '<br>'.
                                    "积分：" . $model->user_integral . '<br>'.
                                    "累计积分：" . $model->accumulate_integral;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label'=> '最后登陆',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return "最后访问IP：" . $model->last_ip . '<br>'.
                                    "最后访问：" . Yii::$app->formatter->asDatetime($model->last_time) . '<br>'.
                                    "访问次数：" . $model->visit_count . '<br>'.
                                    "注册时间：" . Yii::$app->formatter->asDatetime($model->created_at) . '<br>';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template'=> '{ajax-edit} {address}  {recharge} {edit} {status} {destroy}',
                            'buttons' => [
                                'ajax-edit' => function ($url, $model, $key) {
                                    return Html::linkButton(['ajax-edit', 'id' => $model->id], '账号密码', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },
                                'address' => function ($url, $model, $key) {
                                    return Html::linkButton(['address/index', 'member_id' => $model->id], '收货地址');
                                },
                                'recharge' => function ($url, $model, $key) {
                                    return Html::linkButton(['recharge', 'id' => $model->id], '充值', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id]);
                                },
                                'status' => function ($url, $model, $key) {
                                    return Html::status($model->status);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy','id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
        </div>
    </div>
</div>
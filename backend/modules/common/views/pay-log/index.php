<?php
use yii\grid\GridView;
use common\helpers\Html;
use common\enums\StatusEnum;
use common\enums\PayEnum;

$this->title = '支付日志';
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
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        'out_trade_no',
                        [
                            'label' => '支付金额',
                            'value' => function ($model) {
                                $total_fee = $model->total_fee > 0 ? $model->total_fee / 100 : 0 ;
                                $pay_fee = $model->pay_fee > 0 ? $model->pay_fee / 100 : 0 ;
                                $str = '应付金额：' . $total_fee . '<br>';
                                $str .= '实际支付：' . $pay_fee;
                                return $str;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '支付来源',
                            'value' => function ($model) {
                                $str = '订单编号：' . $model->order_sn . '<br>';
                                $str .= '订单类型：' . $model->order_group;
                                return $str;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '支付类型',
                            'value' => function ($model) {
                                return PayEnum::$payTypeExplain[$model->pay_type];
                            },
                            'filter' => Html::activeDropDownList($searchModel, 'pay_type', PayEnum::$payTypeExplain, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'format' => 'raw',
                        ],
                        [
                            'label' => '状态',
                            'value' => function ($model) {
                                if ($model->pay_status == StatusEnum::ENABLED) {
                                    return '<span class="label label-primary">支付成功</span>';
                                } else {
                                    return '<span class="label label-danger">未支付</span>';
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
                            'template'=> '{view}',
                            'buttons' => [
                                'view' => function ($url, $model, $key) {
                                    return Html::linkButton(['view','id' => $model->id], '查看详情', [
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

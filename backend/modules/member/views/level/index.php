<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;

$this->title = '会员等级';
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
                    <?= Html::create(['edit'], '创建') ?>
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
                            'attribute' => 'level',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        'name',
                        [
                            'label' => '升级条件',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                $money = $model->check_money ? '消费金额满' . $model->money . '元' : '';
                                $middle = $model->middle ?
                                    '<b class="red"> 且 </b>' :
                                    '<b class="red"> 或 </b>';
                                $integral = $model->check_integral ? '累计积分满' . $model->integral . '积分' : '';
                                if ($money && $integral) {
                                    $str = $money . $middle . $integral;
                                } else {
                                    $str = (!$money && !$integral) ? '无条件' : ($money ?: $integral);
                                }
                                return $str;
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '折扣',
                            'filter' => false, //不显示搜索框
                            'value' => function ($model) {
                                return $model->discount . '折';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {delete}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['edit', 'id' => $model->id]);
                                },
                                'status' => function ($url, $model, $key) {
                                    if ($model->level == 1) {
                                        return false;
                                    }

                                    return Html::status($model->status);
                                },
                                'delete' => function ($url, $model, $key) {
                                    if ($model->level == 1) {
                                        return false;
                                    }

                                    return Html::delete(['delete', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
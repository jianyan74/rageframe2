<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\enums\AccountTypeEnum;
use common\enums\WhetherEnum;

$this->title = '提现账号';
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
                    'tableOptions' => ['class' => 'table table-hover'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                        ],
                        'realname',
                        'mobile',
                        'account_type_name',
                        [
                            'label' => '账号信息',
                            'format' => 'raw',
                            'value' => function ($model) {
                                if ($model->account_type == AccountTypeEnum::UNION) {
                                    $str = [];
                                    $str[] = '银行账号：' . $model->branch_bank_name;
                                    $str[] = '支行信息：' . $model->account_number;

                                    return implode('<br>', $str);
                                }

                                return $model->ali_number;
                            },
                        ],
                        [
                            'attribute' => 'is_default',
                            'format' => 'raw',
                            'filter' => Html::activeDropDownList($searchModel, 'is_default', WhetherEnum::getMap(), [
                                'prompt' => '全部',
                                'class' => 'form-control'
                            ]),
                            'headerOptions' => ['class' => 'col-md-1'],
                            'value' => function ($model, $key, $index, $column){
                                return Html::whether($model['is_default']);
                            }
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template' => '{edit} {status} {destroy}',
                            'buttons' => [
                                'edit' => function ($url, $model, $key) {
                                    return Html::edit(['ajax-edit', 'id' => $model->id], '编辑', [
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                    ]);
                                },
                                'destroy' => function ($url, $model, $key) {
                                    return Html::delete(['destroy', 'id' => $model->id]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

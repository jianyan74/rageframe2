<?php

use yii\grid\GridView;
use common\helpers\Html;
use common\helpers\ImageHelper;

$this->title = $title;
$this->params['breadcrumbs'][] = ['label' => $this->title];

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?= $this->title; ?></h3>
                <div class="box-tools">
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
                            'label' => '关联用户',
                            'attribute' => 'member_id',
                            'filter' => Html::activeTextInput($searchModel, 'member_id', [
                                    'class' => 'form-control',
                                    'placeholder' => '用户ID'
                                ]
                            ),
                            'value' => function ($model) {
                                return "用户ID：" . $model->member->id . '<br>' .
                                    "昵称：" . $model->member->nickname . '<br>' .
                                    "账号：" . $model->member->username . '<br>' .
                                    "手机：" . $model->member->mobile . '<br>';
                            },
                            'format' => 'raw',
                        ],
                        [
                            'label' => '变动数量',
                            'attribute' => 'num',
                        ],
                        'remark',
                        [
                            'label' => '变动记录',
                            'attribute' => 'new_num',
                            'filter' => Html::activeTextInput($searchModel, 'new_num', [
                                    'class' => 'form-control',
                                    'placeholder' => '最终数量'
                                ]
                            ),
                            'value' => function ($model) {
                                $operational = $model->num < 0 ? '-' : '+';
                                return $model->old_num . $operational . abs($model->num) . '=' . $model->new_num;
                            },
                        ],
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>
</div>

<?php
use common\helpers\Html;
use yii\grid\GridView;

$this->title = '商户申请';
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
                    'tableOptions' => ['class' => 'table table-hover rf-table'],
                    'columns' => [
                        [
                            'class' => 'yii\grid\SerialColumn',
                            'visible' => false, // 不显示#
                        ],
                        'id',
                        'company_name',
                        'title',
                        [
                            'attribute' => 'cate.title',
                            'label'=> '分类',
                            'filter' => Html::activeDropDownList($searchModel, 'cate_id', $cates, [
                                    'prompt' => '全部',
                                    'class' => 'form-control'
                                ]
                            ),
                            'format' => 'raw',
                            'headerOptions' => ['class' => 'col-md-1'],
                        ],
                        'mobile',
                        [
                            'attribute' => 'created_at',
                            'filter' => false, //不显示搜索框
                            'format' => ['date', 'php:Y-m-d H:i:s'],
                        ],
                        [
                            'header' => "操作",
                            'class' => 'yii\grid\ActionColumn',
                            'template'=> '{pass} {refuse}',
                            'buttons' => [
                                'pass' => function ($url, $model, $key) {
                                    return Html::a('通过', ['pass', 'id' => $model->id], [
                                        'class' => 'blue',
                                        'data-toggle' => 'modal',
                                        'data-target' => '#ajaxModal',
                                        ]);
                                },
                                'refuse' => function ($url, $model, $key) {
                                    return Html::a('拒绝', ['refuse', 'id' => $model->id], [
                                        'class' => 'red'
                                    ]);
                                },
                            ],
                        ],
                    ],
                ]); ?>
            </div>
        </div>
    </div>